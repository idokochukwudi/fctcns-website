<?php
class EmailNotifier {
    public static function sendContactNotification($submission) {
        $to = 'admin@fctcns.edu.ng'; // or from settings
        $subject = "New Contact Form Submission: {$submission['subject']}";
        
        $message = self::buildNotificationEmail($submission);
        $headers = self::buildEmailHeaders($submission['email']);
        
        return mail($to, $subject, $message, $headers);
    }
    
    private static function buildNotificationEmail($submission) {
        // Build HTML email template
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                /* Email styles */
            </style>
        </head>
        <body>
            <h2>New Contact Submission</h2>
            <p><strong>From:</strong> {$submission['name']} ({$submission['email']})</p>
            <p><strong>Subject:</strong> {$submission['subject']}</p>
            <p><strong>Message:</strong></p>
            <div>" . nl2br(htmlspecialchars($submission['message'])) . "</div>
        </body>
        </html>
        ";
    }
}