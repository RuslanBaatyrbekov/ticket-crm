<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Заявка #{{ $ticket->id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">
<div class="max-w-4xl mx-auto bg-white shadow-md rounded-lg p-8">
    <a href="{{ route('admin.tickets.index') }}" class="text-blue-600 mb-4 inline-block">&larr; Назад к списку</a>

    <div class="flex justify-between items-start mb-6 border-b pb-4">
        <div>
            <h1 class="text-2xl font-bold mb-1">Заявка #{{ $ticket->id }}: {{ $ticket->subject }}</h1>
            <p class="text-gray-500">От: {{ $ticket->created_at->format('d.m.Y H:i') }}</p>
        </div>

        <form action="{{ route('admin.tickets.update', $ticket) }}" method="POST" class="flex items-center gap-2">
            @csrf
            @method('PATCH')
            <select name="status" class="border p-2 rounded bg-gray-50">
                @foreach($statuses as $status)
                    <option value="{{ $status->value }}" {{ $ticket->status === $status ? 'selected' : '' }}>
                        {{ $status->label() }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="bg-blue-600 text-white px-3 py-2 rounded hover:bg-blue-700">Обновить</button>
        </form>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-3 gap-8">
        <div class="col-span-1 bg-gray-50 p-4 rounded h-fit">
            <h3 class="font-bold text-gray-700 mb-3 border-b pb-2">Клиент</h3>
            <p class="mb-1"><span class="text-gray-500 block text-xs">Имя:</span> {{ $ticket->customer->name }}</p>
            <p class="mb-1"><span class="text-gray-500 block text-xs">Телефон:</span> {{ $ticket->customer->phone }}</p>
            <p class="mb-1"><span class="text-gray-500 block text-xs">Email:</span> {{ $ticket->customer->email ?? 'Не указан' }}</p>
        </div>

        <div class="col-span-2">
            <h3 class="font-bold text-gray-700 mb-3">Сообщение</h3>
            <div class="bg-gray-50 p-4 rounded mb-6 text-gray-800 whitespace-pre-wrap leading-relaxed border">
                {{ $ticket->content }}
            </div>

            <h3 class="font-bold text-gray-700 mb-3">Прикрепленные файлы</h3>
            @if($ticket->getMedia('tickets')->count() > 0)
                <ul class="space-y-2">
                    @foreach($ticket->getMedia('tickets') as $media)
                        <li>
                            <a href="{{ $media->getUrl() }}" target="_blank" class="flex items-center gap-2 text-blue-600 hover:underline border p-2 rounded hover:bg-blue-50">
                                📄 {{ $media->file_name }} <span class="text-gray-400 text-xs">({{ $media->human_readable_size }})</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-500 italic">Файлов нет.</p>
            @endif
        </div>
    </div>
</div>
</body>
</html>
