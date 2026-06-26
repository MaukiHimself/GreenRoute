@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-green-600 dark:focus:border-green-700 focus:ring-green-500 dark:focus:ring-green-700 rounded-md shadow-sm']) }}>
