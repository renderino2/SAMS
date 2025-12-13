window.addEventListener('DOMContentLoaded', () => {
  fetch('../php/get_user_settings.php')
    .then((res) => res.json())
    .then((data) => {
      if (data.success) {
        document.getElementById('full_name').value = data.user.full_name;
        document.getElementById('email').value = data.user.email;
      }
    })
    .catch((err) => {
      document.getElementById('message').textContent = "❌ Failed to load settings.";
    });
});

document.getElementById('settingsForm').addEventListener('submit', async (e) => {
  e.preventDefault();
  const full_name = document.getElementById('full_name').value;
  const email = document.getElementById('email').value;
  const password = document.getElementById('password').value;
  const message = document.getElementById('message');

  const res = await fetch('../php/update_user_settings.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ full_name, email, password }),
  });

  const result = await res.json();

  if (result.success) {
    message.style.color = 'green';
    message.textContent = '✅ Settings updated successfully.';
  } else {
    message.style.color = 'red';
    message.textContent = '❌ ' + result.error;
  }
});
