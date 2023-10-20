<template>
    <input class="border-0 py-1.5 rounded" type="text" id="dtSearch" placeholder="Search" @keyup.capture.enter="$emit('searchString', $event.target.value)" />
</template>
