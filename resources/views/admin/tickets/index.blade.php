<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Админ-панель</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">
<div class="max-w-6xl mx-auto bg-white shadow-md rounded-lg p-6">
    <h1 class="text-2xl font-bold mb-6">Заявки клиентов</h1>

    <form method="GET" class="flex gap-4 mb-6 bg-gray-50 p-4 rounded border">
        <input type="date" name="date" value="{{ request('date') }}" class="border p-2 rounded">

        <select name="status" class="border p-2 rounded">
            <option value="">Все статусы</option>
            @foreach($statuses as $status)
                <option value="{{ $status->value }}" {{ request('status') == $status->value ? 'selected' : '' }}>
                    {{ $status->label() }}
                </option>
            @endforeach
        </select>

        <input type="text" name="search" value="{{ request('search') }}" placeholder="Email или телефон..." class="border p-2 rounded w-64">

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Найти</button>
        <a href="{{ route('admin.tickets.index') }}" class="text-gray-500 flex items-center px-4">Сбросить</a>
    </form>

    <table class="w-full text-left border-collapse">
        <thead>
        <tr class="border-b bg-gray-50">
            <th class="p-3">ID</th>
            <th class="p-3">Клиент</th>
            <th class="p-3">Тема</th>
            <th class="p-3">Статус</th>
            <th class="p-3">Дата</th>
            <th class="p-3">Действия</th>
        </tr>
        </thead>
        <tbody>
        @foreach($tickets as $ticket)
            <tr class="border-b hover:bg-gray-50">
                <td class="p-3">{{ $ticket->id }}</td>
                <td class="p-3">
                    <div class="font-bold">{{ $ticket->customer->name }}</div>
                    <div class="text-sm text-gray-500">{{ $ticket->customer->phone }}</div>
                </td>
                <td class="p-3">{{ $ticket->subject }}</td>
                <td class="p-3">
                        <span class="px-2 py-1 rounded text-xs font-bold
                            {{ $ticket->status->value === 'new' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $ticket->status->value === 'in_progress' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $ticket->status->value === 'processed' ? 'bg-gray-100 text-gray-800' : '' }}
                        ">
                            {{ $ticket->status->label() }}
                        </span>
                </td>
                <td class="p-3 text-sm text-gray-500">{{ $ticket->created_at->format('d.m.Y H:i') }}</td>
                <td class="p-3">
                    <a href="{{ route('admin.tickets.show', $ticket) }}" class="text-blue-600 hover:underline">Просмотр</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $tickets->links() }}
    </div>
</div>
</body>
</html>
