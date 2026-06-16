<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; }
        .header { text-align: center; color: #333; }
        .info-box { border: 1px solid #ddd; padding: 20px; margin-top: 20px; }
        .item { margin-bottom: 10px; }
        label { font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>ملف بيانات الحساب</h1>
        <p>مرحباً بك، {{ $user->name }}</p>
    </div>

    <div class="info-box">
        <div class="item">
            <label>الاسم الكامل:</label> <span>{{ $user->name }}</span>
        </div>
        <div class="item">
            <label>البريد الإلكتروني:</label> <span>{{ $user->email }}</span>
        </div>
        <div class="item">
            <label>تاريخ التسجيل:</label> <span>{{ $user->created_at->format('Y-m-d') }}</span>
        </div>
    </div>
</body>
</html>