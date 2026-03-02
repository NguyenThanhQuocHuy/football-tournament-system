@echo off
echo [%date% %time%] run >> "C:\xampp_8.1\htdocs\Kltn\data\mail_cron.log"
"C:\xampp_8.1\php\php.exe" "C:\xampp_8.1\htdocs\Kltn\scripts\send_match_reminders.php" >> "C:\xampp_8.1\htdocs\Kltn\data\mail_cron.log" 2>&1
    