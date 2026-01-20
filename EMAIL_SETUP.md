# Email Configuration Guide

This guide will help you configure email settings for your Laravel application using Gmail.

## Step 1: Get a Gmail App Password

Since Gmail requires 2-factor authentication for app access, you need to create an App Password:

1. Go to your Google Account: https://myaccount.google.com/
2. Click on **Security** in the left sidebar
3. Under "How you sign in to Google", click **2-Step Verification** (enable it if not already enabled)
4. Scroll down and click **App passwords**
5. Select **Mail** as the app and **Other (Custom name)** as the device
6. Enter "Laravel Portfolio" as the name
7. Click **Generate**
8. **Copy the 16-character password** (you'll need this for your .env file)

## Step 2: Configure Your .env File

Add these lines to your `.env` file in the root of your project:

```env
# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=immanuelatwork@gmail.com
MAIL_PASSWORD=your-16-character-app-password-here
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=immanuelatwork@gmail.com
MAIL_FROM_NAME="Dara Emmanuel"
```

**Important:** Replace `your-16-character-app-password-here` with the actual App Password you generated in Step 1.

## Step 3: Clear Configuration Cache

After updating your `.env` file, clear the configuration cache:

```bash
php artisan config:clear
php artisan cache:clear
```

## Step 4: Test Email Configuration

You can test your email configuration by:

1. **Using Tinker:**
   ```bash
   php artisan tinker
   ```
   Then run:
   ```php
   Mail::raw('Test email', function($message) {
       $message->to('immanuelatwork@gmail.com')
                ->subject('Test Email');
   });
   ```

2. **Submit the contact form** on your website - it should send you an email notification.

## Email Features in This Application

The following features use email:

1. **Contact Form Notifications** (`app/Http/Controllers/ContactController.php`)
   - Sends an email to you when someone submits the contact form
   - Email is sent to: `MAIL_FROM_ADDRESS`
   - Reply-To is set to the contact's email address

2. **Email Verification** (Laravel Fortify)
   - Sends verification emails when users register
   - Configured in `config/fortify.php`

3. **Password Reset** (Laravel Fortify)
   - Sends password reset links via email

## Troubleshooting

### "Connection could not be established" Error

- Make sure you're using an **App Password**, not your regular Gmail password
- Verify `MAIL_PORT=587` and `MAIL_ENCRYPTION=tls`
- Check that 2-Step Verification is enabled on your Google account

### "Authentication failed" Error

- Double-check your App Password (it should be 16 characters, no spaces)
- Make sure `MAIL_USERNAME` is your full Gmail address
- Regenerate the App Password if needed

### Emails going to spam

- Make sure `MAIL_FROM_ADDRESS` matches your Gmail address
- Consider setting up SPF and DKIM records for your domain (if using a custom domain)

## Alternative: Using Other Email Services

If you prefer not to use Gmail, you can use:

- **Mailgun**: Professional email service (free tier available)
- **SendGrid**: Popular email service (free tier available)
- **Postmark**: Great for transactional emails
- **Amazon SES**: Cost-effective for high volume

For these services, update `MAIL_MAILER` and the corresponding credentials in your `.env` file.

