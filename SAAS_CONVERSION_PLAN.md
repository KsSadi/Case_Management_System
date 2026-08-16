# Case Management System → SaaS Conversion Plan

## 1. বর্তমান সিস্টেম বিশ্লেষণ (Current System Analysis)

### এটা আসলে কী?
এটি একটি **আইনি/মামলা ট্র্যাকিং সিস্টেম (Litigation / Court Case Tracking Tool)** — ল ফার্মের জন্য জেনেরিক প্র্যাকটিস-ম্যানেজমেন্ট সফটওয়্যার নয়, বরং একটি নির্দিষ্ট প্রতিষ্ঠানের (কোডে দেখা যায় "Prathomik") নিজস্ব মামলাগুলো ট্র্যাক করার জন্য বানানো একটি **অভ্যন্তরীণ (single-tenant) ড্যাশবোর্ড অ্যাপ**।

প্রমাণ:
- বাংলাদেশ-নির্দিষ্ট আইনি পরিভাষা: **আদালত (Court), বিভাগ (Division), আইনজীবী/Advocate, নিষ্পত্তি (Nispotti = মামলার নিষ্পত্তি/সমাধান)**
- আলাদাভাবে **হাইকোর্ট বিভাগ (High Court Division)** ও **আপিল বিভাগ (Appellate Division)** এর মামলা ট্র্যাক করার মডিউল আছে
- প্রতিটি মামলার শুনানির তারিখ (hearing date), পরবর্তী তারিখ, এবং নিষ্পত্তি স্ট্যাটাস আলাদাভাবে লগ হয় (`histories` টেবিল)

### টেক স্ট্যাক
| Layer | Technology |
|---|---|
| Backend | Laravel 12 (PHP 8.2+) |
| Frontend | Blade + Tailwind CSS 2.x + Alpine.js (সার্ভার-রেন্ডারড, SPA না) |
| UI Template | Midone (Tailwind admin template) |
| Database | MySQL |
| Auth/Permission | `spatie/laravel-permission` v6, dual guard (`admin`, `users`) |
| API | নেই বললেই চলে (শুধু ড্যাশবোর্ড, কোনো পাবলিক REST API নেই) |

### বর্তমান মূল মডিউল
- Case CRUD (`case_items`)
- Case History / শুনানি লগ (`histories`) + পুরনো/ওভারডিউ ভিউ + নিষ্পত্তি ভিউ
- হাইকোর্ট ও আপিল বিভাগের আলাদা মডিউল
- মাস্টার ডেটা: Types, Divisions, Courts, Projects, Advocates, Companies
- রিপোর্ট: Filter report, Date-range report, Monthly report
- ইউজার/এডমিন/রোল ম্যানেজমেন্ট (Spatie roles: `god`, `admin`, `subAdmin`, `manager`, `sale`, `viewer`)
- Important Links মডিউল

### বড় সীমাবদ্ধতা (SaaS বানানোর আগে যেগুলো ঠিক করতে হবে)
1. **কোনো Multi-tenancy নেই** — `companies` টেবিল আছে ঠিকই, কিন্তু সেটা শুধু একটা tag ফিল্ড (কেসের সাথে সংশ্লিষ্ট কোম্পানির নাম), tenant boundary না। সব ডেটা (advocates, courts, admins, roles) গ্লোবাল — একটামাত্র organization এর জন্য।
2. **Loose relations** — `case_items.division`, `project`, `case_type`, `court_name`, `adv_name` — এগুলো আসল foreign key না, শুধু string/int কলাম যেগুলো id হোল্ড করে (কোনো DB-level constraint নেই)।
3. **Authorization ছড়িয়ে-ছিটিয়ে আছে** — প্রতিটা controller-এ আলাদা করে `can()` চেক করা হয়েছে, কোনো centralized Policy/Gate নেই। `BlockViewerRole` মিডলওয়্যার viewer role হলে পুরো ড্যাশবোর্ডে 500 error ছুঁড়ে দেয় — এটা একটা hacky প্যাচ, প্রপার 403+Policy হওয়া উচিত।
4. **Dead schema** — `branches`, `routes` টেবিল আছে migration-এ কিন্তু কোনো model/controller নেই (জেনেরিক Spatie multi-auth starter kit থেকে অবশিষ্টাংশ)।
5. **ডকুমেন্ট ম্যানেজমেন্ট, বিলিং, নোটিফিকেশন, ক্যালেন্ডার, পাবলিক API — কিছুই নেই।**

---

## 1A. বর্তমান মডিউল অনুযায়ী Field ও Relation (বিস্তারিত)

> মাইগ্রেশন ফাইল ও `app/Models/*` সরাসরি পড়ে বের করা প্রকৃত (as-is) ফিল্ড লিস্ট ও রিলেশন। "Relation" কলামে `hasOne(...)` মানে এটা Eloquent-এ define করা আছে ঠিকই, কিন্তু নিচে যেখানে ⚠️ চিহ্ন আছে সেখানে DB-level foreign key constraint **নেই** — কলামটা আসলে plain `string`/`integer`, শুধু id সংরক্ষণ করে।

### Module 1: Case (`case_items`) — মূল মামলা মডিউল, `CaseController`

| Field | Type | Nullable | নোট |
|---|---|---|---|
| `id` | bigint (PK) | না | |
| `case_no` | text | না | মামলা নম্বর |
| `division` | string | না | ⚠️ আসলে `divisions.id` ধরে রাখে, real FK না |
| `project` | string | না | ⚠️ আসলে `projects.id` ধরে রাখে, real FK না |
| `case_type` | string | না | ⚠️ আসলে `types.id` ধরে রাখে, real FK না |
| `court_name` | string | না | ⚠️ আসলে `courts.id` ধরে রাখে, real FK না |
| `parties_name` | string | না | বাদী/বিবাদীর নাম |
| `case_details` | text | না | |
| `case_subject` | string | না | |
| `first_order` | string | হ্যাঁ | |
| `adv_name` | string | হ্যাঁ | ⚠️ আসলে `advocates.id` ধরে রাখে, real FK না |
| `company_id` | unsignedBigInteger | হ্যাঁ | সংযুক্ত কোম্পানি ট্যাগ, later যোগ হয়েছে |
| `created_at`, `updated_at` | timestamp | — | |

**Relations (`app/Models/CaseItem.php`):**
- `projects()` → `hasOne(Project::class, 'id', 'project')`
- `divisions()` → `hasOne(Division::class, 'id', 'division')`
- `types()` → `hasOne(Type::class, 'id', 'case_type')`
- `courts()` → `hasOne(Court::class, 'id', 'court_name')`
- `advocates()` → `hasOne(Advocate::class, 'id', 'adv_name')`
- `companies()` → `hasOne(Company::class, 'id', 'company_id')`
- বিপরীত দিক থেকে: `History` মডেল থেকে `case_id` দিয়ে reverse link (নিচে দেখুন), কিন্তু `CaseItem`-এ `histories()` নামে কোনো `hasMany` define করা নেই (মিসিং — যদিও ব্যবহারিকভাবে `HistoryController`-এ ম্যানুয়ালি query করা হয়)

### Module 2: Case History / শুনানি লগ (`histories`) — `HistoryController`

| Field | Type | Nullable | নোট |
|---|---|---|---|
| `id` | bigint (PK) | না | |
| `case_id` | integer | না | ⚠️ `case_items.id` ধরে রাখে, real FK constraint নেই |
| `date` | date | না | শুনানির তারিখ |
| `past_date` | date | না | আগের তারিখ |
| `next_date` | date | হ্যাঁ (পরে nullable করা হয়েছে) | পরবর্তী শুনানির তারিখ |
| `status` | string | হ্যাঁ (পরে nullable করা হয়েছে) | |
| `is_nispotti` | boolean | না, ডিফল্ট `false` | মামলা নিষ্পত্তি হয়েছে কিনা |
| `nispotti_date` | date | হ্যাঁ | নিষ্পত্তির তারিখ |
| `created_at`, `updated_at` | timestamp | — | |

**Relations (`app/Models/History.php`):**
- `cases()` → `hasOne(CaseItem::class, 'id', 'case_id')` ⚠️ (নামকরণ misleading — আসলে এটা `belongsTo`-এর মতো আচরণ করে যদিও `hasOne` লেখা, কারণ `case_id` এখানে local key হিসেবে ব্যবহৃত হচ্ছে `id`-এর বিপরীতে)

### Module 3: High Court Case (`high_court_cases`) — `HighCourtCaseController`

| Field | Type | Nullable | নোট |
|---|---|---|---|
| `id` | bigint (PK) | না | |
| `case_no` | string | না | |
| `parties_name` | string | না | |
| `case_details` | text | হ্যাঁ | পরে যোগ হয়েছে |
| `first_order` | text | হ্যাঁ | |
| `last_order` | text | হ্যাঁ | |
| `created_at`, `updated_at` | timestamp | — | |

**Relations:** কোনো Eloquent relation define করা নেই — সম্পূর্ণ standalone টেবিল, `case_items`-এর সাথে কোনো লিংক নেই।

### Module 4: Appellate Case (`appellate_cases`) — `AppellateCaseController`

`high_court_cases`-এর সাথে হুবহু একই কাঠামো (duplicated schema):

| Field | Type | Nullable |
|---|---|---|
| `id` | bigint (PK) | না |
| `case_no` | string | না |
| `parties_name` | string | না |
| `case_details` | text | হ্যাঁ |
| `first_order` | text | হ্যাঁ |
| `last_order` | text | হ্যাঁ |
| `created_at`, `updated_at` | timestamp | — |

**Relations:** নেই, standalone।

### Module 5: মাস্টার ডেটা (Types, Divisions, Courts, Projects, Advocates, Companies)

এই ৬টি টেবিলের গঠন একদম অভিন্ন — শুধু নাম সংরক্ষণকারী lookup টেবিল:

| Field | Type | Nullable |
|---|---|---|
| `id` | bigint (PK) | না |
| `name` | string | না |
| `created_at`, `updated_at` | timestamp | — |

| Module | Controller | `case_items`-এ ব্যবহৃত হয় |
|---|---|---|
| Types (মামলার ধরন) | `CaseTypeController` | `case_items.case_type` |
| Divisions (বিভাগ) | `CaseDivisionController` | `case_items.division` |
| Courts (আদালত) | `CourtController` | `case_items.court_name` |
| Projects (প্রজেক্ট) | `ProjectController` | `case_items.project` |
| Advocates (আইনজীবী) | `AdvocateController` | `case_items.adv_name` |
| Companies (কোম্পানি) | `CompanyController` | `case_items.company_id` |

**Relations:** এই টেবিলগুলোর নিজস্ব কোনো Eloquent relation নেই (কোনো `hasMany` back to `CaseItem`) — সম্পর্কটা শুধুই `CaseItem` মডেলের দিক থেকে one-directional (`hasOne`)।

### Module 6: Important Links (`important_links`) — `ImportantLinkController`

| Field | Type | Nullable |
|---|---|---|
| `id` | bigint (PK) | না |
| `name` | string | না |
| `link` | text | না |
| `created_at`, `updated_at` | timestamp | — |

**Relations:** নেই, standalone (যেমন সাইডবারে "সুপ্রিম কোর্ট ওয়েবসাইট" লিংক)।

### Module 7: Admin / User Accounts

**`admins`** (আসল ড্যাশবোর্ড অপারেটর, guard = `admin`) — `AdminsController`:

| Field | Type | Nullable |
|---|---|---|
| `id` | bigint (PK) | না |
| `name` | string | না |
| `email` | string, unique | না |
| `username` | string, unique | না |
| `email_verified_at` | timestamp | হ্যাঁ |
| `password` | string | না |
| `remember_token` | string | হ্যাঁ |
| `created_at`, `updated_at` | timestamp | — |

**Relations (`app/Models/Admin.php`):** `HasRoles` trait (Spatie) → `roles()`, `permissions()` (guard `admin`)

**`users`** (Breeze scaffolding, বাস্তবে ব্যবহার সীমিত, guard = `web`) — `UsersController`:

| Field | Type | Nullable |
|---|---|---|
| `id` | bigint (PK) | না |
| `name` | string | না |
| `email` | string, unique | না |
| `email_verified_at` | timestamp | হ্যাঁ |
| `password` | string | না |
| `remember_token` | string | হ্যাঁ |
| `created_at`, `updated_at` | timestamp | — |

**Relations:** `HasRoles` trait (guard `web`, কিন্তু ব্যবহারিকভাবে dashboard-এর কোনো controller `Auth::guard('admin')` চেক করে — এই guard কার্যত unused)

### Module 8: Roles & Permissions (Spatie `laravel-permission`) — `RolesController`

| Table | Fields |
|---|---|
| `permissions` | `id`, `name`, `guard_name`, timestamps — unique(`name`,`guard_name`) |
| `roles` | `id`, `name`, `guard_name`, timestamps — unique(`name`,`guard_name`) |
| `model_has_permissions` | `permission_id`, `model_type`, `model_id` (polymorphic morph, composite PK) |
| `model_has_roles` | `role_id`, `model_type`, `model_id` (polymorphic morph, composite PK) |
| `role_has_permissions` | `permission_id`, `role_id` (composite PK) |

**Relations:** Many-to-many polymorphic — একজন `Admin` বা `User` একাধিক `Role` নিতে পারে, একটা `Role`-এর একাধিক `Permission` থাকতে পারে। `model_type` কলাম দিয়ে `Admin` ও `User` উভয়েই একই টেবিল শেয়ার করে (আলাদা `guard_name` দিয়ে আলাদা রাখা হয়)।

---

**সারসংক্ষেপ — সবচেয়ে গুরুত্বপূর্ণ পর্যবেক্ষণ:** `case_items`-এর প্রায় সব "রিলেশন" (division, project, case_type, court_name, adv_name) আসলে **string কলামে লুকানো foreign id** — কোনো DB constraint বা cascade delete নেই। SaaS-এ রূপান্তরের সময় এগুলোকে real `unsignedBigInteger` + `foreign()->constrained()` FK-তে বদলানো এবং `tenant_id` স্কোপিং যোগ করা — দুটোই একসাথে করাটাই সবচেয়ে efficient হবে (§4.2-এ described)।

---

## 2. SaaS Product Name — ৫টি সাজেশন

| # | নাম | কেন |
|---|---|---|
| 1 | **Mamla** (মামলা) | সরাসরি বাংলা শব্দ "মামলা" থেকে — বাংলাদেশ/ভারতীয় উপমহাদেশের আইনি বাজারে সহজে মনে থাকার মতো, ব্র্যান্ডেবল, ছোট ডোমেইন সম্ভব (mamla.app / getmamla.com) |
| 2 | **Nispotti** (নিষ্পত্তি) | এই সিস্টেমের সবচেয়ে ইউনিক concept (case disposal tracking) থেকে নেওয়া — legal-tech স্পেসে কেউ এই নাম ব্যবহার করছে না, একদম distinctive |
| 3 | **CourtDesk** | আন্তর্জাতিক/ইংরেজিভাষী মার্কেটের জন্য সহজবোধ্য, "case work করার ডেস্ক" ধারণা বোঝায়, generic legal-tech নামের সাথে মেলে (Zendesk-এর মতো পরিচিত প্যাটার্ন) |
| 4 | **AdvocateFlow** | Workflow-centric নাম — শুনানি, নিষ্পত্তি, রিপোর্টের flow-কে হাইলাইট করে, B2B SaaS হিসেবে professional শোনায় |
| 5 | **DocketBD** | "Docket" (আদালতের মামলার তালিকা) + BD (Bangladesh) — লোকাল আইডেন্টিটি বজায় রেখে গ্লোবাল legal-tech টার্মিনোলজি ব্যবহার করে, পরে অন্য দেশে expand করলে শুধু suffix বদলানো যায় (DocketIN, DocketPK ইত্যাদি) |

**আমার সাজেশন:** যদি প্রথমে বাংলাদেশ/সাউথ এশিয়ার আইনজীবী-প্রতিষ্ঠান টার্গেট করেন → **Mamla** বা **Nispotti** (স্ট্রং লোকাল ব্র্যান্ড আইডেন্টিটি)। যদি শুরু থেকেই গ্লোবাল/ইংরেজিভাষী মার্কেট টার্গেট করেন → **CourtDesk** বা **AdvocateFlow**।

---

## 3. প্রস্তাবিত SaaS সিস্টেম লজিক

### 3.1 Multi-tenancy Model
**Shared database, shared schema, `tenant_id` column** পদ্ধতি সুপারিশ করছি (আলাদা DB per tenant না — কম complexity, সহজ scaling, cheaper infra):

```
Tenant (Organization / Law Firm)
   └── has many Admins/Users (with roles scoped to that tenant)
   └── has many Case Items, Histories, High Court/Appellate Cases
   └── has many Advocates, Courts (can override/extend global reference data)
   └── has own Subscription/Plan
   └── has own branding (logo, color, subdomain)
```

- প্রতিটি tenant-scoped টেবিলে `tenant_id` কলাম যোগ হবে + Laravel **Global Scope** দিয়ে অটোমেটিক ফিল্টার (query করার সময় ম্যানুয়ালি `where('tenant_id', ...)` লেখা লাগবে না)
- সাবডোমেইন-বেসড রাউটিং: `acmelawfirm.mamla.app` অথবা path-based `mamla.app/acmelawfirm`
- Global reference data (যেমন বাংলাদেশের standard courts/divisions list) সিস্টেম-লেভেলে seed করা থাকবে, কিন্তু tenant নিজের কাস্টম court/division/case-type যোগ করতে পারবে

### 3.2 Roles & Permission (per-tenant scoped)
Spatie permission-এর **Teams feature** ব্যবহার করে প্রতিটি tenant-এ আলাদা role set:
- **Owner** — পুরো organization-এর মালিক, billing access
- **Admin** — সব case/user manage করতে পারে
- **Manager** — case create/edit, কিন্তু user management না
- **Advocate/Lawyer** — নিজের assigned case দেখা/আপডেট করা
- **Viewer** — শুধু দেখার অধিকার (current viewer role-এর মতো, কিন্তু 500-abort hack বাদ দিয়ে প্রপার policy দিয়ে)

Authorization রিফ্যাক্টর: প্রতিটি controller-এ ছড়ানো `can()` চেকের বদলে Laravel **Policy classes** (`CasePolicy`, `HistoryPolicy` ইত্যাদি) + route middleware।

### 3.3 Subscription & Billing Logic
| Plan | সীমা (উদাহরণ) |
|---|---|
| Free/Trial | ১০টা active case, ২ জন user, ১৪ দিন trial |
| Basic | ১০০টা case, ৫ জন user, email reminder |
| Pro | Unlimited case, ২০ জন user, SMS reminder, document upload, রিপোর্ট এক্সপোর্ট |
| Enterprise | Unlimited সব, custom branding, API access, priority support |

- Payment gateway: বাংলাদেশ মার্কেটের জন্য **SSLCommerz/bKash**, গ্লোবাল হলে **Stripe**
- Usage-limit middleware: প্ল্যানের সীমা অতিক্রম করলে soft-block + upgrade prompt
- Super-admin (system owner) প্যানেল — সব tenant, subscription, revenue দেখার জন্য

### 3.4 Core Workflow (অপরিবর্তিত রেখে যা কাজ করে সেটাই ধরে রাখা)
1. Case তৈরি হয় → court/division/type/advocate assign হয়
2. প্রতিটা শুনানির পর নতুন `history` এন্ট্রি → পরবর্তী তারিখ সেট হয়
3. Reminder (নতুন): শুনানির ১-২ দিন আগে assigned advocate/admin-কে email/SMS/in-app notification
4. মামলা শেষ হলে "নিষ্পত্তি" মার্ক করে disposal date সেট
5. Dashboard-এ আজকের/আগামীকালের/আগামী ৭ দিনের শুনানি — এটা already আছে, রাখা হবে

---

## 4. প্রস্তাবিত Database Schema

### 4.1 নতুন Core Tables (Multi-tenancy + SaaS-এর জন্য)

```sql
tenants
  id, name, slug (subdomain), logo_path, primary_color,
  subscription_plan_id, trial_ends_at, is_active, created_at

subscription_plans
  id, name, price_monthly, price_yearly, max_cases, max_users,
  max_storage_mb, features_json, is_active

subscriptions
  id, tenant_id, plan_id, status (trial/active/past_due/cancelled),
  starts_at, ends_at, payment_gateway, gateway_subscription_id

invoices
  id, tenant_id, subscription_id, amount, status, invoice_no,
  paid_at, payment_method, gateway_transaction_id

documents (নতুন ফিচার — case-এর ফাইল সংযুক্তি)
  id, tenant_id, case_id, case_type (enum: case_item/high_court/appellate),
  file_name, file_path, file_size, uploaded_by, created_at

notifications
  id, tenant_id, user_id, type (hearing_reminder/case_update),
  channel (email/sms/in_app), payload_json, read_at, sent_at

audit_logs
  id, tenant_id, user_id, action, model_type, model_id,
  old_values_json, new_values_json, ip_address, created_at
```

### 4.2 বিদ্যমান Tables — Multi-tenant করার জন্য পরিবর্তন

সব নিচের টেবিলে `tenant_id` (FK → `tenants.id`, indexed) যোগ হবে:

| Table | পরিবর্তন |
|---|---|
| `case_items` | + `tenant_id`; `division`/`project`/`case_type`/`court_name`/`adv_name` → real FK-তে রূপান্তর |
| `histories` | + `tenant_id`; `case_id` → real FK with cascade |
| `high_court_cases`, `appellate_cases` | + `tenant_id` |
| `advocates`, `courts`, `divisions`, `types`, `projects`, `companies` | + `tenant_id` (nullable = গ্লোবাল/শেয়ার্ড ডেটা, না হলে tenant-specific) |
| `admins`/`users` | + `tenant_id` (একজন user একাধিক tenant-এ থাকলে আলাদা pivot টেবিল `tenant_user`) |
| `roles`, `permissions` | Spatie Teams feature দিয়ে `team_id` (=tenant_id) কলাম |
| `important_links` | + `tenant_id` |

বাদ দেওয়া/পরিষ্কার করা:
- `branches`, `routes` টেবিল — ব্যবহার হয় না, migration থেকে বাদ দেওয়া বা মুছে ফেলা
- `BlockViewerRole` মিডলওয়্যার-এর 500-abort হ্যাক সরিয়ে প্রপার `403` + Policy-based gate

---

## 5. পূর্ণাঙ্গ Feature List

### 5.1 Core Features (বিদ্যমান, বজায় রাখা)
- Case CRUD + case detail, parties, subject, first order
- Hearing history log (তারিখ, পরের তারিখ, স্ট্যাটাস)
- নিষ্পত্তি (disposal) tracking — active/old/disposed ভিউ, বছর/মাস অনুযায়ী ফিল্টার
- হাইকোর্ট বিভাগ ও আপিল বিভাগের আলাদা কেস মডিউল
- মাস্টার ডেটা ম্যানেজমেন্ট: Courts, Divisions, Case Types, Advocates, Projects, Companies
- রিপোর্ট: Multi-criteria filter report, Date-range report, Monthly report (প্রিন্ট-ফ্রেন্ডলি বাংলা হেডার সহ)
- Dashboard stats: আজ/আগামীকাল/আগামী ৭ দিনের শুনানি
- Role-based access (god/admin/manager/sale/viewer প্যাটার্ন)

### 5.2 নতুন SaaS-Specific Features
- **Multi-tenant organization onboarding** — sign-up flow, subdomain বাছাই, trial শুরু
- **Subscription & billing** — plan selection, upgrade/downgrade, invoice history, payment gateway (SSLCommerz/Stripe)
- **Plan-based usage limits** — case/user/storage সীমা, soft-limit warning
- **Document management** — প্রতিটা কেসে ফাইল/PDF/ছবি আপলোড (order copy, evidence)
- **Hearing reminders** — email + SMS (Twilio/local SMS gateway) + in-app notification, শুনানির X দিন আগে
- **Calendar view/sync** — মাসিক ক্যালেন্ডার ভিউ, Google Calendar/ICS export
- **Client/party portal (optional, higher plan)** — সীমিত read-only অ্যাক্সেস দিয়ে মামলার status দেখা
- **Audit trail** — কে কখন কী পরিবর্তন করলো, compliance-এর জন্য জরুরি
- **Custom branding per tenant** — লোগো, রঙ, প্রিন্ট রিপোর্টে tenant-এর নাম
- **Data export** — PDF/Excel রিপোর্ট এক্সপোর্ট
- **REST API** — ইন্টিগ্রেশন ও mobile app-এর জন্য (Sanctum দিয়ে)
- **Two-factor authentication** — সিকিউরিটির জন্য
- **Multi-language toggle** — বাংলা/ইংরেজি UI
- **Super-admin panel** — system owner-এর জন্য সব tenant, revenue, usage monitor করার প্যানেল
- **Data backup/export per tenant** — plan অনুযায়ী retention policy

### 5.3 ভবিষ্যতের জন্য (Roadmap, v2+)
- Mobile app (React Native/Flutter) — API-based
- AI-assisted case summary/next-step suggestion
- WhatsApp notification integration
- e-Filing/court-system ইন্টিগ্রেশন (যদি সরকারি API থাকে)
- Time tracking + billing per hour (ল ফার্মদের জন্য আলাদা মডিউল হিসেবে)

---

## 6. টেকনিক্যাল মাইগ্রেশন রোডম্যাপ (সংক্ষিপ্ত)

1. **Phase 1 — Foundation**: `tenants` টেবিল, tenant middleware/global scope, সব বিদ্যমান টেবিলে `tenant_id` যোগ, existing single-org ডেটাকে একটা default tenant-এ migrate করা
2. **Phase 2 — Auth রিফ্যাক্টর**: dual-guard (`users`/`admin`) সিস্টেমকে একক tenant-aware auth-এ একত্র করা, `BlockViewerRole` হ্যাক সরিয়ে Policy-based authorization
3. **Phase 3 — Billing**: `subscription_plans`, `subscriptions`, `invoices` টেবিল + payment gateway ইন্টিগ্রেশন + usage-limit middleware
4. **Phase 4 — নতুন ফিচার**: document upload, notifications/reminders, audit log
5. **Phase 5 — Polish**: custom branding, API, multi-language, super-admin analytics panel
6. **সবসময়**: dead schema (`branches`, `routes`) ক্লিন-আপ, loose string-based relation → real FK constraint

---

*এই ডকুমেন্ট বর্তমান কোডবেসের (Laravel 12, single-tenant Bengali litigation tracker) বিশ্লেষণের ভিত্তিতে তৈরি — SaaS রূপান্তরের প্রাথমিক পরিকল্পনা হিসেবে ব্যবহার করা যেতে পারে। বাস্তবায়নের আগে প্রতিটা phase নিয়ে আলাদাভাবে বিস্তারিত planning করা উচিত।*
