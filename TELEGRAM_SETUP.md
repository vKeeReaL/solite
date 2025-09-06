# 🚀 Telegram Bot Setup Guide for Solite Candles

This guide will help you set up a Telegram bot to receive notifications when customers submit the contact form on your website.

## 📋 Prerequisites

- A Telegram account
- Access to your hosting environment (PHP support required)
- Ability to set environment variables or edit PHP files

## 🔧 Step 1: Create a Telegram Bot

### 1.1 Start a Chat with BotFather
1. Open Telegram and search for `@BotFather`
2. Start a chat with BotFather
3. Send `/start` to begin

### 1.2 Create Your Bot
1. Send `/newbot` to BotFather
2. Choose a name for your bot (e.g., "Solite Candles Contact Bot")
3. Choose a username (must end with 'bot', e.g., "solite_contact_bot")
4. BotFather will give you a **Bot Token** - save this!

### 1.3 Get Your Chat ID
1. Start a chat with your new bot
2. Send any message to the bot
3. Visit this URL in your browser (replace `YOUR_BOT_TOKEN` with your actual token):
   ```
   https://api.telegram.org/botYOUR_BOT_TOKEN/getUpdates
   ```
4. Look for the `"chat":{"id":123456789}` in the response
5. Save the **Chat ID** number

## 🌐 Step 2: Configure Your Hosting Environment

### Option A: Environment Variables (Recommended)
If your hosting supports environment variables:

1. Set these environment variables:
   ```
   TELEGRAM_BOT_TOKEN=your_bot_token_here
   TELEGRAM_CHAT_ID=your_chat_id_here
   ```

2. The PHP code will automatically use these values.

### Option B: Direct PHP Configuration
If environment variables aren't available:

1. Edit `api/contact.php`
2. Find these lines:
   ```php
   $bot_token = getenv('TELEGRAM_BOT_TOKEN') ?: 'YOUR_BOT_TOKEN_HERE';
   $chat_id = getenv('TELEGRAM_CHAT_ID') ?: 'YOUR_CHAT_ID_HERE';
   ```

3. Replace with your actual values:
   ```php
   $bot_token = '1234567890:ABCdefGHIjklMNOpqrsTUVwxyz';
   $chat_id = '123456789';
   ```

## 📁 Step 3: Upload Files to Your Hosting

1. Upload the `api/contact.php` file to your hosting
2. Ensure it's accessible at `yourdomain.com/api/contact.php`
3. Make sure the file has proper permissions (usually 644)

## 🧪 Step 4: Test Your Setup

1. Fill out the contact form on your website
2. Submit the form
3. Check your Telegram bot for the notification message
4. The message should look like:
   ```
   🕯️ New Contact Form Submission

   👤 Name: John Doe
   📧 Email: john@example.com
   📝 Subject: General Inquiry
   💬 Message:
   Hello, I love your candles!

   ⏰ Time: 2024-01-15 14:30:25
   🌐 Website: Solite Candles
   ```

## 🔒 Security Considerations

### Rate Limiting
- The bot includes basic validation to prevent spam
- Consider adding CAPTCHA for additional protection

### Input Sanitization
- All form inputs are sanitized to prevent XSS attacks
- Email validation is implemented

### CORS Headers
- The API includes CORS headers for cross-origin requests
- Adjust these if needed for your specific domain

## 🐛 Troubleshooting

### Bot Not Responding
1. Check if the bot token is correct
2. Ensure the bot hasn't been blocked
3. Verify the chat ID is correct

### Form Submission Fails
1. Check browser console for JavaScript errors
2. Verify the API endpoint URL is correct
3. Check hosting error logs for PHP errors

### Telegram API Errors
1. Check if your bot token is valid
2. Ensure the bot has permission to send messages
3. Verify the chat ID is correct

## 📱 Advanced Features

### Custom Bot Commands
You can add commands to your bot by sending `/setcommands` to BotFather:

```
start - Start the bot
help - Show help information
status - Check bot status
```

### Webhook Setup (Optional)
For real-time notifications, you can set up webhooks instead of polling:

```php
// In your bot setup
$webhook_url = "https://yourdomain.com/api/telegram-webhook.php";
$telegram_url = "https://api.telegram.org/bot{$bot_token}/setWebhook";
```

## 📞 Support

If you encounter issues:
1. Check the Telegram Bot API documentation
2. Verify your hosting supports PHP and cURL
3. Test with a simple PHP script first

## 🎯 Next Steps

Once your bot is working:
1. Customize the notification message format
2. Add additional validation rules
3. Implement auto-replies for common inquiries
4. Set up multiple chat IDs for team notifications

---

**Note:** Keep your bot token secure and never share it publicly. If compromised, regenerate it with BotFather using `/revoke`.

