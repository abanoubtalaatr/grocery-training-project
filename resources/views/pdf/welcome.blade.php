<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>مرحباً بك في تطبيقنا</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif; /* DejaVu Sans تدعم اللغة العربية في DomPDF */
            margin: 40px;
            color: #333;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #4f46e5;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #4f46e5;
            font-size: 24px;
        }
        .content {
            font-size: 16px;
        }
        .user-details {
            background-color: #f3f4f6;
            border: 1px solid #e5e7eb;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>مرحباً بك في Grocery!</h1>
    </div>

    <div class="content">
        <p>عزيزنا المشترك <strong>{{ $name }}</strong>،</p>
        <p>يسعدنا جداً انضمامك إلينا في تطبيق Grocery. هذا الملف تم إنشاؤه تلقائياً كملف ترحيبي بك وتأكيد اشتراكك.</p>
        
        <div class="user-details">
            <h3>بيانات الحساب الخاص بك:</h3>
            <ul>
                <li><strong>الاسم:</strong> {{ $name }}</li>
                <li><strong>البريد الإلكتروني:</strong> {{ $email }}</li>
            </ul>
        </div>

        <p>نأمل أن تحظى بتجربة رائعة معنا. إذا كان لديك أي استفسار، فلا تتردد في الاتصال بنا.</p>
    </div>

    <div class="footer">
        <p>هذا المستند تم إنشاؤه برمجياً بواسطة Laravel & DomPDF لأغراض تعليمية.</p>
    </div>
</body>
</html>
