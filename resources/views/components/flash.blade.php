@if (session('status'))
    <div class="mb-4 rounded-md bg-green-100 p-3 text-sm text-green-800">{{ session('status') }}</div>
@endif
@if ($errors->has('delete'))
    <div class="mb-4 rounded-md bg-red-100 p-3 text-sm text-red-800">{{ $errors->first('delete') }}</div>
@endif
