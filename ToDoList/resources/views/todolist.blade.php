@extends('layouts.app')
<title>Lista zadań</title>
<script>window.Laravel = {csrfToken: '{{ csrf_token() }}'}</script>
<h1>Lista zadań</h1>
<div id="app">
    <listview-component></listview-component>
</div>
