<?php

class Slave
{
    private int $id;
    public string $nickName;
    public string $sex;
    public int $age;
    public int $weight;
    public string $colorSkin;
    public string $placeOf;
    public string $hobby;
    public int $rentPrice;
    public int $salePrice;
    public array $category;
    public string $rentTimeStart;
    public string $rentTimeEnd;



    public function __construct(
        int $id,
        string $nickName,
        string $sex,
        int $age,
        int $weight,
        string $colorSkin,
        string $placeOf,
        string $hobby,
        int $rentPrice,
        int $salePrice,
        array $category,
        string $rentTimeStart,
        string $rentTimeEnd
    ) {
        $this->nickName = $nickName;
        $this->sex = $sex;
        $this->age = $age;
        $this->weight = $weight;
        $this->colorSkin = $colorSkin;
        $this->placeOf = $placeOf;
        $this->hobby = $hobby;
        $this->rentPrice = $rentPrice;
        $this->salePrice = $salePrice;
        $this->category = $category;
        $this->id = $id;
        $this->rentTimeStart = $rentTimeStart;
        $this->rentTimeEnd = $rentTimeEnd;
    }



    public function getName(): string
    {
        return $this->nickName;
    }

    public function renderOne($id)
    {
        if ($this->id == $id) {
            $html = "<h2>" . $this->getName() . "</h2>\n";
            $html .= "<p> <b>Пол:</b> " . $this->sex;
            $html .= " <b>Возраст:</b> " . $this->age . " лет";
            $html .= " <b>Вес:</b> " . $this->weight . " кг.</p>\n";
            $html .= "<p> <b>Цвет кожи:</b> " . $this->colorSkin . " ";
            $html .= "<b>Пойман/Выращен:</b> " . $this->placeOf . " ";
            $html .= "<b>Хобби:</b> " . $this->hobby . " </p>\n";
            $html .= '<form method="POST">';
            $html .= '<label>Выберите диапазон даты и времени:</label>';
            $html .= '<input type="datetime-local" name="datetime__start" min="2023-01-01T00:00" max="2024-12-31T23:59">';
            $html .= '<input type="datetime-local" name="datetime__end" min="2023-01-01T00:00" max="2024-12-31T23:59">';
            $html .= '<input type="submit" name="submit" value="Отправить">';
            $html .= '</form>';

            if (isset($_POST['submit'])) {
                if (!empty($_POST['datetime__start']) && !empty($_POST['datetime__end'])) {
                    $start = $_POST['datetime__start'];
                    $end = $_POST['datetime__end'];
                    $this->rent($start, $end);
                }
            }

            return $html;
        }
    }

    public function rent($start, $end)
    {
        // переводм в формат дат
        $dateStart = new DateTime($start);
        $dateEnd = new DateTime($end);

        // переводим в часы
        $unix_timeStart = $dateStart->format('U');
        $unix_timeEnd = $dateEnd->format('U');

        $remaining_hoursStart = 24 - $dateStart->format('H');
        $remaining_hoursEnd = $dateEnd->format('H');

        $remaining_daysStart = $dateStart->format('d');
        $remaining_daysEnd = $dateEnd->format('d');

        $diff_days = $remaining_daysEnd - $remaining_daysStart - 1;

        $price = ($this->rentPrice * 16) * $diff_days + $remaining_hoursStart * $this->rentPrice + $remaining_hoursEnd * $this->rentPrice;


        $hours = ($unix_timeEnd - $unix_timeStart) / 3600.0;


        if ($this->rentTimeStart) {
            $rentTimeStart = new DateTime($this->rentTimeStart);
            $rentTimeEnd = new DateTime($this->rentTimeEnd);

            if (

                (!(($rentTimeStart->format('U') < $unix_timeStart && $rentTimeEnd->format('U') < $unix_timeStart)
                    || ($rentTimeStart->format('U') > $unix_timeEnd && $rentTimeEnd->format('U') > $unix_timeEnd)))
            ) {
                echo 'время начала';
                echo $dateStart->format('d.m.Y H:i:s');
                echo "<br>";
                echo "время окончания";
                echo $dateEnd->format('d.m.Y H:i:s');
                echo "<br>";
                echo "Недопустимое время аренды <br> Вы заняли диапазон от " . $rentTimeStart->format('d.m.Y H:i:s') . " до " . $rentTimeEnd->format('d.m.Y H:i:s');
            } else {
                $this->check($hours, $remaining_hoursStart, $remaining_hoursEnd, $dateStart, $dateEnd, $start, $end, $price);
            }
        } else {
            $this->check($hours, $remaining_hoursStart, $remaining_hoursEnd, $dateStart, $dateEnd, $start, $end, $price);
        }
    }

    public function check($hours, $remaining_hoursStart, $remaining_hoursEnd, $dateStart, $dateEnd, $start, $end,  $price)
    {
        if ($hours < 0) {
            echo "Время аренды не может быть нулевым или отрицательным";
        } else if ($remaining_hoursStart > 16) {
            echo 'время начала';
            echo $dateStart->format('d.m.Y H:i:s');
            echo "<br>";
            echo "время окончания";
            echo $dateEnd->format('d.m.Y H:i:s');
            echo "<br>";
            echo "время аренды превышает суточную норму в 16 часов в день начала аренды";
        } else if ($hours > 16 && $remaining_hoursEnd > 16) {
            echo 'время начала';
            echo $dateStart->format('d.m.Y H:i:s');
            echo "<br>";
            echo "время окончания";
            echo $dateEnd->format('d.m.Y H:i:s');
            echo "<br>";
            echo "время аренды превышает суточную норму в 16 часов";
        } else {
            $this->rentTimeStart =  $start;
            $this->rentTimeEnd = $end;
            echo "Аренда прошла успешно";
            echo "<br>";
            echo "Арендован в " . $dateStart->format('d.m.Y H:i:s') . " по " . $dateEnd->format('d.m.Y H:i:s') . "<br>";
            echo "Стоимость аренды в рублях " . $price . "<br>";
        }
    }


    public function renderAll()
    {
        $html = "<h2>" . $this->getName() . "</h2>\n";
        $html .= "<a href='rent.php?id={$this->id}'> Аренда </a>";
        $html .= "<p> <b>Пол:</b> " . $this->sex;
        $html .= " <b>Возраст:</b> " . $this->age . " лет";
        $html .= " <b>Вес:</b> " . $this->weight . " кг.</p>\n";
        $html .= "<p> <b>Цвет кожи:</b> " . $this->colorSkin . " ";
        $html .= "<b>Пойман/Выращен:</b> " . $this->placeOf . " ";
        $html .= "<b>Хобби:</b> " . $this->hobby . " </p>\n";

        return $html;
    }
}
