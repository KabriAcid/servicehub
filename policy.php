<?php
class Page {
    private $title;
    private $content;

    public function __construct($title = "Page", $content = "") {
        $this->title = $title;
        $this->content = $content;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function setContent($content) {
        $this->content = $content;
    }

    public function render() {
        echo "<!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>{$this->title}</title>
            <style>
                body {
                    font-family: 'Segoe UI', sans-serif;
                    background-color: #f4f8fb;
                    color: #333;
                    margin: 0;
                    padding: 0;
                }
                header {
                    background-color: #007bff;
                    color: white;
                    padding: 20px;
                    text-align: center;
                }
                .container {
                    max-width: 960px;
                    margin: 40px auto;
                    background-color: #fff;
                    padding: 30px;
                    box-shadow: 0 0 10px rgba(0,0,0,0.05);
                    border-radius: 8px;
                }
                h1, h2 {
                    color: #0056b3;
                }
                p {
                    line-height: 1.7;
                }
                ul {
                    margin-left: 20px;
                }
                footer {
                    background-color: #007bff;
                    color: white;
                    text-align: center;
                    padding: 10px;
                    position: relative;
                    bottom: 0;
                    width: 100%;
                }
            </style>
        </head>
        <body>
            <header>
                <h1>{$this->title}</h1>
            </header>
            <div class='container'>
                {$this->content}
            </div>
            <footer>
                &copy; " . date('Y') . " ServiceHub. All rights reserved.
            </footer>
        </body>
        </html>";
    }
}

$page = new Page();
$page->setTitle("Privacy Policy & Terms");

$page->setContent("
    <h2>Privacy Policy</h2>
    <p>At <strong>ServiceHub</strong>, we respect your privacy. This policy outlines how we collect, use, and protect your personal information.</p>
    <p><strong>Information We Collect:</strong></p>
    <ul>
        <li>Personal data (name, email, phone number)</li>
        <li>Location and service preferences</li>
        <li>Device information and usage logs</li>
    </ul>

    <p><strong>How We Use Your Information:</strong></p>
    <ul>
        <li>To connect you with verified professionals</li>
        <li>To improve user experience and platform reliability</li>
        <li>To send important updates or promotions (with your consent)</li>
    </ul>

    <p><strong>Data Protection:</strong> We use encryption, secure databases, and access control to protect your data from unauthorized access.</p>

    <h2>Terms of Service</h2>
    <p>By using our platform, you agree to the following terms:</p>
    <ul>
        <li>You will provide accurate and truthful information</li>
        <li>You will not misuse or harm the platform or other users</li>
        <li>You understand that services are provided by independent professionals</li>
    </ul>

    <p>We reserve the right to update this policy and terms. Please check this page periodically.</p>

    <p>For questions, contact us at <a href='mailto:privacy@servicehub.com'>privacy@servicehub.com</a>.</p>
");

$page->render();
?>
