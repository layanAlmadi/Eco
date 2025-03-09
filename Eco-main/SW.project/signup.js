document.getElementById("signupForm").addEventListener("submit", function(event) {
    event.preventDefault(); // منع إعادة تحميل الصفحة

    let username = document.getElementById("username").value.trim();
    let email = document.getElementById("email").value.trim();
    let password = document.getElementById("password").value;
    let confirmPassword = document.getElementById("confirmPassword").value;
    let errorMessage = document.getElementById("errorMessage");

    // التحقق من أن جميع الحقول ممتلئة
    if (!username || !email || !password || !confirmPassword) {
        errorMessage.textContent = "Please fill all the fields.";
        return;
    }

    // التحقق من صحة البريد الإلكتروني
    if (!email.includes("@")) {
        errorMessage.textContent = "Please enter a valid email.";
        return;
    }

    // التحقق من تطابق كلمة المرور
    if (password !== confirmPassword) {
        errorMessage.textContent = "Passwords don't match.";
        return;
    }

    // التحقق من قوة كلمة المرور (مثلاً: 6 أحرف على الأقل)
    if (password.length < 6) {
        errorMessage.textContent = "Password must be at least 8 characters long.";
        return;
    }

    alert("The account has been created successfully.!");
    errorMessage.textContent = ""; // مسح رسالة الخطأ
});
