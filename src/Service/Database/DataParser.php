<?php

namespace App\Service\Database;

class DataParser
{
    /**
     * @param array $data
     *
     * @return array
     *
     * @throws \RuntimeException
     */
    public function parse(array $data) : array
    {
        $titles = array_shift($data);
        foreach ($data as $kd => &$datum) {
            foreach ($datum AS $k => &$item)
            {
                switch ($k)
                {
                    case 2:
                        $item = $this->getRamData($item);
                        break;
                    case 3:
                        $item = $this->getDiskData($item);
                        break;
                    default:
                        continue;
                }
            }
        }

        return $this->setTitles($titles, $data);
    }

    /**
     * @param $item
     * @return array
     */
    protected function getRamData($item)
    {
        if (preg_match('/^(\d+)(\w{2})(\w+)$/i', $item, $matches) === 1)
        {
            $item = [
                'plain' => $item,
                'amount' => $matches[1],
                'unit_measure' => $matches[2],
                'type' => $matches[3],
            ];
        }

        return $item;
    }

    /**
     * @param $item
     * @return array
     */
    protected function getDiskData($item)
    {
        if (preg_match('/^(\d+)x(\d+)(TB|GB)(\w+)$/i', $item, $matches) === 1)
        {
            $item = [
                'plain' => $item,
                'amount' => $matches[1],
                'size' => $matches[2],
                'unit_measure' => $matches[3],
                'type' => $matches[4],
            ];
        }

        return $item;
    }

    /**
     * @param array $titles
     * @param array $data
     * @return array
     */
    protected function setTitles(array $titles, array $data) :array
    {
        foreach ($data as &$datum) {
            $datum = array_combine($titles, $datum);
        }

        return $data;
    }

}
