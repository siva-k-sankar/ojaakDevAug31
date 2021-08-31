@extends('layouts.home')
@section('styles')
<?php
    $urladsid = Request::segment('3');
    $urlsellerid = Request::segment('4');
    $urlid=Auth::user()->uuid.'_'.$urlsellerid.'_'.$urladsid;
?>
<script> window.location = "{{ route('chats',$urlid) }}";</script>
@endsection
