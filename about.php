<?php
class Page {
    private $title;
    private $content;

    public function __construct($title = "Untitled", $content = "") {
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
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    background-color: #f4f8fb;
                    color: #333;
                    margin: 0;
                    padding: 0;
                }
                header {
                    background-color:rgb(22, 57, 94);
                    color: white;
                    padding: 20px;
                    text-align: center;
                }
                .container {
                    max-width: 900px;
                    margin: 30px auto;
                    background-color: #ffffff;
                    padding: 30px;
                    box-shadow: 0 4px 8px rgba(0,0,0,0.05);
                    border-radius: 10px;
                }
                h1 {
                    color:rgb(241, 243, 246);
                }
                p {
                    line-height: 1.6;
                }
                footer {
                    background-color:rgb(19, 54, 92);
                    color: white;
                    text-align: center;
                    padding: 10px;
                    position: fixed;
                    bottom: 0;
                    width: 100%;
                }
                a {
                    color: #0056b3;
                    text-decoration: none;
                }
                a:hover {
                    text-decoration: underline;
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
                &copy; " . date('Y') . " ServiceHub | All rights reserved
            </footer>
        </body>
        </html>";
    }
}

// Create About Page
$page = new Page();
$page->setTitle("About Us");
$page->setContent("
    <p>Welcome to <strong>ServiceHub</strong> – your trusted partner for accessing verified professionals for all your service needs.</p>
    <p>Our platform is built to connect users with experts in various fields including home repair, technology, logistics, and personal services.</p>
    <p>We prioritize quality, reliability, and customer satisfaction. Our mission is to make service delivery seamless, safe, and efficient.</p>
    <p>For inquiries, partnership, or support, feel free to contact us at <a href='mailto:info@servicehub.com'>info@servicehub.com</a>.</p>
");

$page->render();
?>
