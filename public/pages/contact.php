<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - FCT College of Nursing Sciences</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #F8F9FA; color: #2C3E50; }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
        header { background: white; box-shadow: 0 2px 10px rgba(0,0,0,0.1); padding: 1rem 0; }
        .header-content { display: flex; justify-content: space-between; align-items: center; }
        .logo { color: #6B4E9B; font-size: 1.8rem; font-weight: 700; text-decoration: none; }
        nav ul { display: flex; list-style: none; gap: 2rem; }
        nav a { color: #5A6C7D; text-decoration: none; font-weight: 600; }
        main { margin-top: 20px; min-height: 60vh; }
        footer { background: #2C3E50; color: white; text-align: center; padding: 2rem; margin-top: 4rem; }
        .page-header { background: linear-gradient(135deg, #6B4E9B20, #7FB28520); padding: 3rem; border-radius: 10px; margin: 2rem 0; }
        .content-card { background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); margin: 2rem 0; }
    </style>
</head>
<body>
<header>
    <div class="container header-content">
        <a href="/" class="logo">FCT College of Nursing Sciences</a>
        <nav>
            <ul>
                <li><a href="/">Home</a></li>
                <li><a href="/about">About</a></li>
                <li><a href="/programs">Programs</a></li>
                <li><a href="/admissions">Admissions</a></li>
                <li><a href="/contact">Contact</a></li>
            </ul>
        </nav>
    </div>
</header>

<main class="container">
    <div class="page-header">
        <h1>Contact Us</h1>
        <p>We’d love to hear from you</p>
    </div>

    <div class="content-card">
        <h2 style="color:#6B4E9B;">Get in Touch</h2>
        <p><strong>Address:</strong> Federal Capital Territory, Abuja, Nigeria</p>
        <p><strong>Email:</strong> info@fctcns.edu.ng</p>
        <p><strong>Phone:</strong> +234 XXX XXX XXXX</p>

        <p style="margin-top:1.5rem;">
            A contact form will be added in a later stage.
        </p>
    </div>
</main>

<footer>
    <div class="container">
        <p>&copy; <?php echo date('Y'); ?> FCT College of Nursing Sciences. All rights reserved.</p>
    </div>
</footer>
</body>
</html>
