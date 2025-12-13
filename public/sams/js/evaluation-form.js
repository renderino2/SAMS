const form = document.getElementById("evaluationForm");
const message = document.getElementById("message");

form.addEventListener("submit", async function (e) {
  e.preventDefault();

  const data = {
    studentName: document.getElementById("studentName").value.trim(),
    natureOfWork: document.getElementById("natureOfWork").value.trim(),
    office: document.getElementById("office").value.trim(),
    comments: document.getElementById("comments").value.trim(),
    date: document.getElementById("date").value,
    ratedBy: document.getElementById("ratedBy").value.trim(),
    head: document.getElementById("head").value.trim(),
  };

  for (let i = 1; i <= 10; i++) {
    const val = parseInt(document.getElementById(`rate${i}`).value);
    if (val < 1 || val > 10) {
      message.textContent = `❌ Rating ${i} must be from 1 to 10.`;
      message.style.color = "red";
      return;
    }
    data[`rate${i}`] = val;
  }

  try {
    const response = await fetch("../php/submit-evaluation.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(data),
    });

    const result = await response.json();

    if (result.success) {
      message.textContent = `✅ ${result.message}`;
      message.style.color = "green";
      form.reset();
    } else {
      message.textContent = "❌ " + (result.error || "Submission failed.");
      message.style.color = "red";
    }
  } catch (err) {
    message.textContent = "⚠️ Network error.";
    message.style.color = "red";
  }
});
