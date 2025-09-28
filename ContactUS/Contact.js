const form = document.getElementById("contactForm");
const resultMessage = document.getElementById("resultMessage");

// قائمة الحقول والقواعد
const fields = [
    { id: "FullName", pattern: /.+/, error: "Full name is required" },
    { id: "Email", pattern: /^[^\s@]+@[^\s@]+\.[a-z]/, error: "Invalid email format" },
    { id: "NumberPhone", pattern: /^\d{10}$/, error: "Invalid phone format (e.g., 0606060606)" },
    { id: "Subject", pattern: /.+/, error: "Subject is required" },
    { id: "Message", pattern: /.+/, error: "Message is required" },
];

// تحقق من الحقل عند الخروج منه
fields.forEach(({ id, pattern, error }) => {
    const input = document.getElementById(id);
    const feedback = input.nextElementSibling;

    input.addEventListener("input", () => {
    if (pattern.test(input.value)) {
        input.classList.remove("invalid");
        input.classList.add("valid");
        feedback.style.display = "none";
    } else {
        input.classList.remove("valid");
        input.classList.add("invalid");
        feedback.textContent = error;
        feedback.style.display = "block";
    }
    });
});

// تحقق عند الإرسال
form.addEventListener("submit", (e) => {
    e.preventDefault();

    let isValid = true;

    fields.forEach(({ id, pattern, error }) => {
    const input = document.getElementById(id);
    const feedback = input.nextElementSibling;

    if (!pattern.test(input.value)) {
        input.classList.remove("valid");
        input.classList.add("invalid");
        feedback.textContent = error;
        feedback.style.display = "block";
        isValid = false;
    } else {
        input.classList.remove("invalid");
        input.classList.add("valid");
        feedback.style.display = "none";
    }
    });

    if (isValid) {
    resultMessage.textContent = "We have received your message, we will reply to you as soon as possible, thank you.";
    resultMessage.className = "success";
    form.reset(); // مسح القيم من الفورم
    fields.forEach(({ id }) => {
        const input = document.getElementById(id);
        input.classList.remove("valid", "invalid");
    });
    } else {
    resultMessage.textContent = "Please fix the errors in the form.";
    resultMessage.className = "error";
}
});
