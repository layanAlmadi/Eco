document.getElementById("loginForm").addEventListener("submit", function(event) {
    event.preventDefault(); // منع إعادة تحميل الصفحة

    let email = document.getElementById("email").value;
    let password = document.getElementById("password").value;
    let errorMessage = document.getElementById("errorMessage");

    if (email === "" || password === "") {
        errorMessage.textContent = "Please fill all fields.";
        return;
    }

    if (!email.includes("@")) {
        errorMessage.textContent = "Please enter a valid email" ;
        return;
    }

    // في التطبيق الفعلي، يمكنك إرسال البيانات إلى الخادم هنا
    alert("You have successfully logged in!");
    errorMessage.textContent = ""; // مسح رسالة الخطأ
});
