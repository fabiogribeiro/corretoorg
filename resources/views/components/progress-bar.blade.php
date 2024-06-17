<div {{ $attributes->merge(['class' => 'w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700']) }}>
  <div class="bg-blue-600 h-2.5 rounded-full" :class="current === total && 'bg-emerald-500'" :style="'width: ' + current/total*100 + '%'"></div>
</div>
