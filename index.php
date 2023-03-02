<?php
// Функция для нахождения кратчайшего пути в лабиринте с помощью алгоритма Дейкстры
function dijkstra($maze, $start, $finish) {
    $rows = count($maze);
    $cols = count($maze[0]);

    // Инициализация расстояний
    $dist = array();
    for ($i = 0; $i < $rows; $i++) {
        for ($j = 0; $j < $cols; $j++) {
            $dist[$i][$j] = INF; // Изначально расстояние до всех клеток равно бесконечности
        }
    }
    $dist[$start[0]][$start[1]] = 0; // Расстояние от стартовой клетки до нее самой равно 0

    // Инициализация очереди вершин
    $queue = new SplPriorityQueue();
    $queue->insert([$start[0], $start[1]], 0);

    // Инициализация массива предков
    $prev = array();
    for ($i = 0; $i < $rows; $i++) {
        for ($j = 0; $j < $cols; $j++) {
            $prev[$i][$j] = null; // Изначально предок каждой клетки неизвестен
        }
    }

    // Поиск кратчайшего пути
    while (!$queue->isEmpty()) {
        // Извлечение вершины с наименьшим расстоянием
        $current = $queue->extract();
        $cur_row = $current[0];
        $cur_col = $current[1];

        // Проверка, достигли ли мы финишной клетки
        if ($cur_row == $finish[0] && $cur_col == $finish[1]) {
            break;
        }

        // Перебор соседей текущей вершины
        $neighbors = array(
            [$cur_row - 1, $cur_col], // Сосед сверху
            [$cur_row + 1, $cur_col], // Сосед снизу
            [$cur_row, $cur_col - 1], // Сосед слева
            [$cur_row, $cur_col + 1], // Сосед справа
        );
        foreach ($neighbors as $n) {
            $n_row = $n[0];
            $n_col = $n[1];

            // Проверка, что соседняя клетка находится в пределах лабиринта и не является стеной
            if ($n_row >= 0 && $n_row < $rows && $n_col >= 0 && $n_col < $cols && $maze[$n_row][$n_col] != 0) {
                // Вычисление расстояния до соседней клетки
                $alt = $dist[$cur_row][$cur_col] + $maze[$n_row][$n_col];

                // Если новое расстояние меньше текущего, обновляем значение

                if ($alt < $dist[$n_row][$n_col]) {
                    $dist[$n_row][$n_col] = $alt;
                    $prev[$n_row][$n_col] = [$cur_row, $cur_col];
                    $priority = $alt;
                    $queue->insert([$n_row, $n_col], -$priority); // Добавляем соседнюю клетку в очередь
                }
            }
        }
    }

// Восстановление пути
    $path = array();
    $current = $finish;
    while ($current != $start) {
        $path[] = $current;
        $current = $prev[$current[0]][$current[1]];
    }
    $path[] = $start;
    $path = array_reverse($path);

// Возвращаем кратчайший путь и его длину
    return array(
        "path" => $path,
        "distance" => $dist[$finish[0]][$finish[1]]
    );
}


$maze = array(
    array(1, 0, 1, 1),
    array(1, 1, 1, 0),
    array(1, 1, 0, 1),
    array(1, 1, 1, 1),
);
$start = array(0, 0);
$finish = array(3, 3);

$result = dijkstra($maze, $start, $finish);
echo "Кратчайший путь: ";
foreach ($result["path"] as $p) {
    echo "(" . $p[0] . ", " . $p[1] . ") ";
}
echo "\n";
echo "Длина пути: " . $result["distance"] . "\n";