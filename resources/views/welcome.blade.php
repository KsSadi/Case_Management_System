<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CasePro � Legal Case Management System</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --primary:      #1e40af;
            --primary-dark: #1e3a8a;
            --accent:       #3b82f6;
            --accent-light: #dbeafe;
        }

        body { font-family: 'Inter', sans-serif; line-height: 1.6; }

        .btn {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 10px 22px; border-radius: 8px;
            font-size: .9rem; font-weight: 600;
            text-decoration: none; transition: all .2s; cursor: pointer; border: none;
        }
        .btn-lg { padding: 14px 32px; font-size: 1rem; border-radius: 10px; }
        .btn-white { background: #fff; color: var(--primary); }
        .btn-white:hover { background: var(--accent-light); transform: translateY(-1px); }

        .hero {
            min-height: 100vh;
            background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 55%, #1d4ed8 100%);
            display: flex; align-items: center;
            padding: 60px 5%;
            position: relative; overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute; inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .hero-inner {
            max-width: 1200px; margin: 0 auto; width: 100%;
            display: grid; grid-template-columns: 1fr 1fr; gap: 80px; align-items: center;
            position: relative; z-index: 1;
        }
        .hero-badge {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(255,255,255,.12); color: #93c5fd;
            padding: 6px 14px; border-radius: 50px; font-size: .8rem; font-weight: 600;
            margin-bottom: 24px; border: 1px solid rgba(255,255,255,.15);
        }
        .hero-badge span {
            width: 7px; height: 7px; background: #34d399;
            border-radius: 50%; display: inline-block; animation: pulse 1.8s infinite;
        }
        @keyframes pulse { 0%,100%{opacity:1;} 50%{opacity:.4;} }

        .hero h1 {
            font-size: clamp(2.2rem, 4vw, 3.4rem);
            font-weight: 800; line-height: 1.2;
            color: #fff; margin-bottom: 20px;
        }
        .hero h1 span { color: #60a5fa; }
        .hero p { font-size: 1.1rem; color: #cbd5e1; margin-bottom: 36px; max-width: 480px; }
        .hero-actions { display: flex; gap: 16px; flex-wrap: wrap; }

        .hero-card {
            background: rgba(255,255,255,.07);
            border: 1px solid rgba(255,255,255,.15);
            border-radius: 20px; padding: 32px;
            backdrop-filter: blur(10px);
        }
        .hero-card-title {
            color: #93c5fd; font-size: .75rem; font-weight: 700;
            letter-spacing: .1em; text-transform: uppercase; margin-bottom: 20px;
        }
        .stat-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 24px; }
        .stat-item { background: rgba(255,255,255,.08); border-radius: 12px; padding: 18px 16px; text-align: center; }
        .stat-item .num { font-size: 1.8rem; font-weight: 800; color: #fff; display: block; line-height: 1; }
        .stat-item .lbl { font-size: .75rem; color: #94a3b8; margin-top: 4px; }

        .hero-feature-list { list-style: none; }
        .hero-feature-list li { display: flex; align-items: center; gap: 10px; color: #cbd5e1; font-size: .88rem; padding: 6px 0; }
        .hero-feature-list li i { color: #34d399; width: 16px; }

        @media (max-width: 900px) { .hero-inner { grid-template-columns: 1fr; } .hero-card { display: none; } }
        @media (max-width: 600px) { .hero h1 { font-size: 2rem; } }
    </style>
</head>
<body>

<section class="hero">
    <div class="hero-inner">
        <div>
            <div class="hero-badge"><span></span> Legal Case Management System</div>
            <h1>Manage Every <span>Legal Case</span> with Precision</h1>
            <p>One platform to file cases, schedule hearings, manage advocates, and generate reports. Everything your legal team needs, organized and always within reach.</p>
            <div class="hero-actions">
                <a href="{{ route('dashboard.login') }}" class="btn btn-white btn-lg">
                    <i class="fas fa-right-to-bracket"></i> Login to Dashboard
                </a>
            </div>
        </div>

        <div class="hero-card">
            <div class="hero-card-title"><i class="fas fa-chart-line"></i> &nbsp;System Overview</div>
            <div class="stat-grid">
                <div class="stat-item"><span class="num">100%</span><span class="lbl">Case Tracking</span></div>
                <div class="stat-item"><span class="num">24/7</span><span class="lbl">Accessibility</span></div>
                <div class="stat-item"><span class="num">Multi</span><span class="lbl">Role Access</span></div>
                <div class="stat-item"><span class="num">Fast</span><span class="lbl">Reports</span></div>
            </div>
            <ul class="hero-feature-list">
                <li><i class="fas fa-check-circle"></i> Manage cases, courts & advocates</li>
                <li><i class="fas fa-check-circle"></i> Track hearing dates & history</li>
                <li><i class="fas fa-check-circle"></i> Monthly & date-range reports</li>
                <li><i class="fas fa-check-circle"></i> Role-based permission control</li>
                <li><i class="fas fa-check-circle"></i> Division & project management</li>
            </ul>
        </div>
    </div>
</section>

</body>
</html>
