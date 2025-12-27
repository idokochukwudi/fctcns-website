<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FCT College of Nursing Sciences</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #2C3E50;
            background-color: #F8F9FA;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        header {
            background-color: #FFFFFF;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 1rem 0;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            color: #6B4E9B;
            font-size: 1.8rem;
            font-weight: 700;
            text-decoration: none;
        }
        
        .logo span {
            color: #7FB285;
        }
        
        nav ul {
            display: flex;
            list-style: none;
            gap: 2rem;
        }
        
        nav a {
            color: #5A6C7D;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }
        
        nav a:hover {
            color: #6B4E9B;
        }
        
        main {
            margin-top: 80px;
            min-height: 80vh;
        }
        
        .hero {
            text-align: center;
            padding: 6rem 2rem;
            background: linear-gradient(135deg, #6B4E9B20, #7FB28520);
            border-radius: 20px;
            margin: 2rem 0;
        }
        
        .hero h1 {
            color: #2C3E50;
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        
        .hero p {
            color: #5A6C7D;
            font-size: 1.2rem;
            max-width: 600px;
            margin: 0 auto 2rem;
        }
        
        .btn {
            display: inline-block;
            background-color: #6B4E9B;
            color: white;
            padding: 12px 30px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }
        
        .btn:hover {
            background-color: #5A3F8A;
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background-color: #7FB285;
        }
        
        .btn-secondary:hover {
            background-color: #6A9C70;
        }
        
        footer {
            background-color: #2C3E50;
            color: white;
            text-align: center;
            padding: 2rem 0;
            margin-top: 4rem;
        }
        
        .stage-info {
            background-color: white;
            padding: 3rem;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin: 2rem 0;
        }
        
        .stage-info h2 {
            color: #6B4E9B;
            margin-bottom: 1rem;
        }
        
        .checklist {
            list-style: none;
            margin: 1.5rem 0;
        }
        
        .checklist li {
            padding: 0.5rem 0;
            display: flex;
            align-items: center;
        }
        
        .checklist li::before {
            content: "✓";
            color: #7FB285;
            font-weight: bold;
            margin-right: 10px;
        }
        
        .color-swatch {
            display: flex;
            gap: 1rem;
            margin: 2rem 0;
        }
        
        .color-box {
            width: 60px;
            height: 60px;
            border-radius: 8px;
        }
        
        .color-1 { background-color: #6B4E9B; }
        .color-2 { background-color: #7FB285; }
        .color-3 { background-color: #2C3E50; }
        .color-4 { background-color: #F8F9FA; border: 1px solid #E1E8ED; }
        
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 1rem;
            }
            
            nav ul {
                flex-wrap: wrap;
                justify-content: center;
                gap: 1rem;
            }
            
            .hero h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container header-content">
            <a href="/" class="logo">FCT<span>CNS</span></a>
            <nav>
                <ul>
                    <li><a href="/">Home</a></li>
                    <li><a href="/about">About</a></li>
                    <li><a href="/programs">Programs</a></li>
                    <li><a href="/admissions">Admissions</a></li>
                    <li><a href="/contact">Contact</a></li>
                    <li><a href="/admin">Admin</a></li>
                </ul>
            </nav>
        </div>
    </header>
    
    <main class="container">
        <section class="hero">
            <h1>FCT College of Nursing Sciences</h1>
            <p>Empowering Future Healthcare Professionals Since 1989</p>
            <a href="/programs" class="btn">Explore Programs</a>
            <a href="/admissions" class="btn btn-secondary" style="margin-left: 10px;">Apply Now</a>
        </section>
        
        <section class="stage-info">
            <h2>Stage 1: Project Setup Complete! ✅</h2>
            <p>Your development environment is now configured with:</p>
            
            <ul class="checklist">
                <li>XAMPP installed and running</li>
                <li>Virtual host configured (fctcns.local)</li>
                <li>Complete project folder structure created</li>
                <li>Environment files configured (.env, .gitignore)</li>
                <li>Entry point and test page created</li>
            </ul>
            
            <h3>Design Color Palette</h3>
            <p>We'll use these professional, muted colors throughout the site:</p>
            
            <div class="color-swatch">
                <div class="color-box color-1" title="Primary Purple #6B4E9B"></div>
                <div class="color-box color-2" title="Sage Green #7FB285"></div>
                <div class="color-box color-3" title="Charcoal Text #2C3E50"></div>
                <div class="color-box color-4" title="Light Background #F8F9FA"></div>
            </div>
            
            <p><strong>Next Stage:</strong> We'll set up the database and create the carousel table for the homepage.</p>
        </section>
    </main>
    
    <footer>
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> FCT College of Nursing Sciences. All rights reserved.</p>
            <p style="margin-top: 10px; color: #B0BEC5;">Domain: https://fctcns.edu.ng | Developer: idokochukwudi</p>
        </div>
    </footer>
</body>
</html>