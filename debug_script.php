$user = App\Models\User::find(3);
echo "\n--- DEBUG START ---\n";
echo "User Name: " . $user->name . "\n";
echo "Role: " . $user->role . "\n";
echo "District ID: " . $user->district_id . "\n";
echo "Subdistrict ID: " . ($user->subdistrict_id ?? 'NULL') . "\n";
echo "SQL: " . App\Models\Tender::byUserAccess($user)->toSql() . "\n";
echo "Tenders Found: " . App\Models\Tender::byUserAccess($user)->count() . "\n";
echo "--- DEBUG END ---\n";
