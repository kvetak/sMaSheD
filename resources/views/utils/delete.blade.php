<form action="{{ isset($url) ? $url : Request::url() }}" class="form-inline" method="POST">
    {{ method_field('DELETE') }}
    {{ csrf_field() }}
    <button type='submit'
            class="{{ isset($class) ? $class : 'btn btn-link' }}"
            value="{{ isset($value) ? $value  : 'delete' }}">
        {!! isset($text) ? $text : 'delete' !!}
    </button>
</form>