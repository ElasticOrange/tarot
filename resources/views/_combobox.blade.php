<?php
	if (array_search($selected, $options) === false) {
		$options[] = $selected;
	}
?>

<select {!! $attributes !!}
>
	<option></option>
	@foreach($options as $option)
	<option
		@if($option == $selected)
			selected="selected"
		@endif
	>{{ $option }}</option>
	@endforeach
</select>
