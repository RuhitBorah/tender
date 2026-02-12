$phpPath = "D:\xammp\php"
$currentPath = [Environment]::GetEnvironmentVariable("Path", "User")

if ($currentPath -notlike "*$phpPath*") {
    $newPath = "$currentPath;$phpPath"
    [Environment]::SetEnvironmentVariable("Path", $newPath, "User")
    Write-Host "Success: Added D:\xammp\php to your User PATH." -ForegroundColor Green
    Write-Host "Please RESTART your terminal and VS Code for changes to take effect." -ForegroundColor Yellow
} else {
    Write-Host "php is already in your User PATH." -ForegroundColor Cyan
}

# Verify
Write-Host "Current PHP version check (using full path):"
& "D:\xammp\php\php.exe" -v
