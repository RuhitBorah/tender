<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Under Maintenance — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #0f0c29;
            background: linear-gradient(135deg, #0f0c29 0%, #1a1450 40%, #24243e 100%);
            overflow: hidden;
            position: relative;
        }

        /* Animated background blobs */
        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.18;
            animation: float 8s ease-in-out infinite;
        }
        .blob-1 { width: 500px; height: 500px; background: #6366f1; top: -120px; left: -120px; animation-delay: 0s; }
        .blob-2 { width: 400px; height: 400px; background: #a855f7; bottom: -100px; right: -80px; animation-delay: 3s; }
        .blob-3 { width: 300px; height: 300px; background: #3b82f6; bottom: 100px; left: 20%; animation-delay: 5s; }

        @keyframes float {
            0%, 100% { transform: translateY(0px) scale(1); }
            50% { transform: translateY(-30px) scale(1.05); }
        }

        /* Stars */
        .stars {
            position: absolute; inset: 0; overflow: hidden; pointer-events: none;
        }
        .star {
            position: absolute;
            background: white;
            border-radius: 50%;
            animation: twinkle var(--dur, 3s) ease-in-out infinite;
        }
        @keyframes twinkle {
            0%, 100% { opacity: 0.1; }
            50% { opacity: 0.8; }
        }

        .card {
            position: relative;
            z-index: 10;
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 28px;
            padding: 56px 48px;
            max-width: 520px;
            width: 90%;
            text-align: center;
            box-shadow: 0 40px 80px rgba(0,0,0,0.4), inset 0 1px 0 rgba(255,255,255,0.1);
        }

        /* Gear icon */
        .gear-wrap {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 88px;
            height: 88px;
            background: linear-gradient(135deg, #6366f1, #a855f7);
            border-radius: 24px;
            margin-bottom: 28px;
            box-shadow: 0 16px 40px rgba(99,102,241,0.4);
        }
        .gear-svg {
            animation: spin 4s linear infinite;
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Status pill */
        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: rgba(251,146,60,0.15);
            border: 1px solid rgba(251,146,60,0.3);
            color: #fb923c;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 5px 14px;
            border-radius: 99px;
            margin-bottom: 20px;
        }
        .status-dot {
            width: 7px; height: 7px;
            background: #fb923c;
            border-radius: 50%;
            animation: pulse-dot 1.4s ease-in-out infinite;
        }
        @keyframes pulse-dot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(0.75); }
        }

        h1 {
            font-size: 32px;
            font-weight: 800;
            color: #ffffff;
            line-height: 1.2;
            margin-bottom: 14px;
            letter-spacing: -0.5px;
        }
        p.sub {
            font-size: 15px;
            color: rgba(255,255,255,0.55);
            line-height: 1.7;
            margin-bottom: 36px;
        }

        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.12), transparent);
            margin-bottom: 28px;
        }

        .contact-stack { display: flex; flex-direction: column; gap: 10px; }
        .contact-box {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 16px;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 14px;
            text-align: left;
            text-decoration: none;
            transition: background 0.2s, border-color 0.2s;
        }
        .contact-box:hover { background: rgba(255,255,255,0.09); border-color: rgba(255,255,255,0.16); }
        .contact-box.whatsapp { border-color: rgba(37,211,102,0.3); background: rgba(37,211,102,0.07); }
        .contact-box.whatsapp:hover { background: rgba(37,211,102,0.13); }
        .contact-icon {
            width: 42px; height: 42px; flex-shrink: 0;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
        }
        .contact-icon.blue  { background: linear-gradient(135deg, #3b82f6, #6366f1); }
        .contact-icon.green { background: linear-gradient(135deg, #25d366, #128c54); }
        .contact-label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: rgba(255,255,255,0.35);
            margin-bottom: 3px;
            display: flex; align-items: center; gap: 6px;
        }
        .badge-recommended {
            background: #25d366; color: #000;
            font-size: 9px; font-weight: 700;
            padding: 1px 6px; border-radius: 99px;
            letter-spacing: 0.05em;
        }
        .contact-value {
            font-size: 14px;
            font-weight: 500;
            color: #a5b4fc;
        }
        .contact-value.green { color: #6ee7a0; }

        .footer-note {
            margin-top: 32px;
            font-size: 12px;
            color: rgba(255,255,255,0.2);
        }
    </style>
</head>
<body>

    <!-- Background blobs -->
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>

    <!-- Stars -->
    <div class="stars" id="stars"></div>

    <div class="card">
        <!-- Gear icon -->
        <div class="gear-wrap">
            <svg class="gear-svg" width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="3"></circle>
                <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
            </svg>
        </div>

        <!-- Status -->
        <div class="status-pill">
            <span class="status-dot"></span>
            Under Maintenance
        </div>

        <!-- Heading -->
        <h1>This site is in development mode.</h1>
        <p class="sub">
            If you need a pre-demo or require access, please get in touch with us via WhatsApp or email below.
        </p>

        <div class="divider"></div>

        <!-- Contact blocks -->
        <div class="contact-stack">

            <!-- WhatsApp (recommended) -->
            <a href="https://wa.me/919435822026" target="_blank" class="contact-box whatsapp">
                <div class="contact-icon green">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="white">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                        <path d="M12 0C5.373 0 0 5.373 0 12c0 2.125.558 4.118 1.528 5.845L.057 23.643a.5.5 0 0 0 .625.601l5.963-1.932A11.94 11.94 0 0 0 12 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.882a9.877 9.877 0 0 1-5.031-1.378l-.36-.214-3.733 1.209 1.141-3.629-.235-.374A9.858 9.858 0 0 1 2.118 12C2.118 6.54 6.54 2.118 12 2.118S21.882 6.54 21.882 12 17.46 21.882 12 21.882z"/>
                    </svg>
                </div>
                <div>
                    <div class="contact-label">
                        WhatsApp
                        <span class="badge-recommended">RECOMMENDED</span>
                    </div>
                    <div class="contact-value green">+91 94358 22026</div>
                </div>
            </a>

            <!-- Email -->
            <a href="mailto:hello@dekarusys.dev" class="contact-box">
                <div class="contact-icon blue">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                        <polyline points="22,6 12,13 2,6"></polyline>
                    </svg>
                </div>
                <div>
                    <div class="contact-label">Email</div>
                    <div class="contact-value">hello@dekarusys.dev</div>
                </div>
            </a>

        </div>

        <p class="footer-note">&copy; dekarusys. {{ now()->format('Y') }}</p>
    </div>

    <script>
        // Generate twinkling stars
        const container = document.getElementById('stars');
        for (let i = 0; i < 80; i++) {
            const s = document.createElement('div');
            s.className = 'star';
            const size = Math.random() * 2.5 + 0.5;
            s.style.cssText = `
                width:${size}px; height:${size}px;
                top:${Math.random()*100}%;
                left:${Math.random()*100}%;
                --dur:${(Math.random()*3+2).toFixed(1)}s;
                animation-delay:${(Math.random()*4).toFixed(1)}s;
            `;
            container.appendChild(s);
        }
    </script>
</body>
</html>
