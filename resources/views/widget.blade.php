<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Обратная связь</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { overflow: hidden; background: transparent; }
    </style>
</head>
<body class="p-4 bg-white/95 rounded-xl shadow-lg border border-gray-100 h-full">

<div id="form-container">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Напишите нам</h2>

    <div id="error-box" class="hidden bg-red-100 text-red-700 p-3 rounded mb-4 text-sm"></div>

    <form id="feedback-form" class="space-y-3">
        <div>
            <input type="text" name="name" placeholder="Ваше имя" required
                   class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 transition">
        </div>
        <div>
            <input type="tel" name="phone" placeholder="Телефон (+7...)" required
                   class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 transition">
        </div>
        <div>
            <input type="email" name="email" placeholder="Email (необязательно)"
                   class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 transition">
        </div>
        <div>
            <input type="text" name="subject" placeholder="Тема обращения" required
                   class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 transition">
        </div>
        <div>
                <textarea name="message" rows="3" placeholder="Текст сообщения..." required
                          class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 transition"></textarea>
        </div>
        <div>
            <label class="block text-sm text-gray-600 mb-1">Прикрепить файл</label>
            <input type="file" name="file" class="w-full text-sm text-gray-500">
        </div>

        <button type="submit" id="submit-btn"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-200">
            Отправить
        </button>
    </form>
</div>

<div id="success-box" class="hidden flex flex-col items-center justify-center h-full text-center space-y-4">
    <div class="text-green-500 text-5xl">✓</div>
    <h3 class="text-2xl font-bold text-gray-800">Спасибо!</h3>
    <p class="text-gray-600">Ваша заявка принята. Менеджер свяжется с вами.</p>
</div>

<script>
    const form = document.getElementById('feedback-form');
    const submitBtn = document.getElementById('submit-btn');
    const errorBox = document.getElementById('error-box');
    const formContainer = document.getElementById('form-container');
    const successBox = document.getElementById('success-box');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        errorBox.classList.add('hidden');
        errorBox.innerText = '';
        submitBtn.disabled = true;
        submitBtn.innerText = 'Отправка...';

        const formData = new FormData(form);

        try {
            const response = await fetch('/api/tickets', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                },
                body: formData
            });

            const data = await response.json();

            if (response.ok) {
                formContainer.classList.add('hidden');
                successBox.classList.remove('hidden');
            } else {
                let errorMsg = data.message || 'Произошла ошибка';

                if (data.errors) {
                    errorMsg = Object.values(data.errors).flat().join('\n');
                }

                throw new Error(errorMsg);
            }
        } catch (error) {
            errorBox.innerText = error.message;
            errorBox.classList.remove('hidden');
            submitBtn.disabled = false;
            submitBtn.innerText = 'Отправить';
        }
    });
</script>
</body>
</html>
