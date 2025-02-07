<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Page</title>
    <script>
        async function submitForm(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);
            const response = await fetch('/subscribe', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            const result = await response.json();
            document.getElementById('response').innerText = JSON.stringify(result);
        }
    </script>
</head>
<body>
    <form onsubmit="submitForm(event)">
        <label for="url">URL:</label>
        <input type="text" id="url" name="url" required>
        <br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br>
        <button type="submit">Subscribe</button>
    </form>
    <div id="response"></div>
</body>
</html>
