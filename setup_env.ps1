$phpPath = "D:\xammp\php"
$env:Path = "$env:Path;$phpPath"
Write-Host "Added $phpPath to current session PATH." -ForegroundColor Green

# Verify
Write-Host "Verifying PHP version:"
php -v

if ($LASTEXITCODE -eq 0) {
    Write-Host "PHP is reachable." -ForegroundColor Green
    Write-Host "Clearing cache..."
    php artisan optimize:clear
} else {
    Write-Host "Failed to reach PHP." -ForegroundColor Red
}
