<?php
/**
 * User: Yusuf Abdillah Putra
 * Date: 11/1/2020
 * Time: 1:51 PM
 */

namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Database\Capsule\Manager as DB;

class QueryHelper
{
    private $request;
    private $defaultPage;
    private $defaultPerPage;

    /**
     * QueryHelper constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->defaultPage = (int)1;
        $this->defaultPerPage = (int)5;
    }

    /**
     * @param array $select
     * @param string $from
     * @param string|null $distinct
     * @param string|null $rawFilter
     * @return array
     */
    public function get(array $select, string $from, string $distinct = null, $rawFilter = null, bool $toString = false): array
    {
        /**
         * Generate Filter
         */
        if (!empty($rawFilter)) {
            $filter = ' WHERE 1 = 1 ' . $this->generateFilter($select) . $rawFilter;
        } else {
            $filter = ' WHERE 1 = 1 ' . $this->generateFilter($select);
        }

        /**
         * Konversi alias array ke string
         */
        $alias = $this->alias($select);

        /**
         * Melakukan distinct
         */
        if (!empty($distinct)) {
            $prepareStatement = 'SELECT ' . $this->distinct($distinct) . ' ' . $alias->main . ' ' . $from;
        } else {
            $prepareStatement = 'SELECT ' . $alias->main . $from;
        }
        /**
         * Bersihkan whitespace query param agar query lebih minimized
         */
        $cleanWhiteSpaceParamQuery = preg_replace("/\r|\n|\s\s+/", " ", trim($prepareStatement));
        $statement = trim('SELECT COUNT(*) OVER() AS total_row, ' . $alias->sub . ' FROM (' . $cleanWhiteSpaceParamQuery . ') main');

        /**
         * Pagination dan Order
         */
        $page = $this->request->all('page', $this->defaultPage);
        $perPage = $this->request->all('per_page', $this->defaultPerPage);

        dd($page);
        dd($perPage);
        $formulaOffset = (($page * $perPage) - $perPage);
        if (!empty($this->request->all('order'))) {
            $arrayOrder = array();
            foreach ($this->request->all('order') as $key => $value) {
                $orderStatement = empty($value) ? $key : '"' . $key . '"' . ' ' . $value;
                array_push($arrayOrder, $orderStatement);
            }
            $statement = $statement . $filter . ' ORDER BY ' . implode(' , ', $arrayOrder) . ' ' . ' LIMIT ' . $perPage . ' OFFSET ' . $formulaOffset;
        } else {
            $statement = $statement . $filter . ' LIMIT ' . $perPage . ' OFFSET ' . $formulaOffset;
        }

        /**
         * Eksekusi Hasil Query yang dibangun
         */
        $fetch = DB::select(DB::raw(trim($statement)));
        return $this->resultSerialize($fetch, $page, $perPage, $toString);
    }

    /**
     * @param array $select
     * @param string $from
     * @param string|null $distinct
     * @param string|null $rawFilter
     * @return array
     */
    public function checkSQL(array $select, string $from, string $distinct = null, $rawFilter = null): array
    {
        /**
         * Generate Filter
         */
        if (!empty($rawFilter)) {
            $filter = ' WHERE 1 = 1 ' . $this->generateFilter($select) . $rawFilter;
        } else {
            $filter = ' WHERE 1 = 1 ' . $this->generateFilter($select);
        }

        /**
         * Konversi alias array ke string
         */
        $alias = $this->alias($select);

        /**
         * Melakukan distinct
         */
        if (!empty($distinct)) {
            $prepareStatement = 'SELECT ' . $this->distinct($distinct) . ' ' . $alias->main . ' ' . $from;
        } else {
            $prepareStatement = 'SELECT ' . $alias->main . $from;
        }
        /**
         * Bersihkan whitespace query param agar query lebih minimized
         */
        $cleanWhiteSpaceParamQuery = preg_replace("/\r|\n|\s\s+/", " ", trim($prepareStatement));
        $statement = trim('SELECT COUNT(*) OVER() AS total_row, ' . $alias->sub . ' FROM (' . $cleanWhiteSpaceParamQuery . ') main');

        /**
         * Pagination dan Order
         */
        $page = $this->request->all('page', $this->defaultPage);
        $perPage = $this->request->all('per_page', $this->defaultPerPage);
        $formulaOffset = (($page * $perPage) - $perPage);
        if (!empty($this->request->all('order'))) {
            $arrayOrder = array();
            foreach ($this->request->all('order') as $key => $value) {
                $orderStatement = empty($value) ? $key : '"' . $key . '"' . ' ' . $value;
                array_push($arrayOrder, $orderStatement);
            }
            $statement = $statement . $filter . ' ORDER BY ' . implode(' , ', $arrayOrder) . ' ' . ' LIMIT ' . $perPage . ' OFFSET ' . $formulaOffset;
        } else {
            $statement = $statement . $filter . ' LIMIT ' . $perPage . ' OFFSET ' . $formulaOffset;
        }

        return [
            'query' => $statement,
            'filter' => $filter,
            'select' => $select,
            'distinct' => $distinct
        ];
    }

    /**
     * @param array $filter
     * @return string|null
     */
    public function setfilter(array $filter)
    {
        $filterCollection = array();
        $isFilter = false;
        $loop = 0;
        foreach ($filter as $inputParam => $query) {
            if (!empty($this->request->all($inputParam))) {
                $loop++;
                if ($loop == 1) {
                    $isFilter = true;
                }
                array_push($filterCollection, $query);
            }
        }
        if ($isFilter) {
            return ' AND ' . implode(' AND ', $filterCollection);
        } else {
            return null;
        }
    }

    /**
     * @param array $select
     * @return string
     */
    private function generateFilter(array $select)
    {
        $filterCollection = array();
        $isFilter = false;
        $loop = 0;
        foreach ($select as $column => $alias) {
            /**
             * Is NULL
             */
            $paramIsNull = $this->request->all($alias . '_null', 2);
            if (!empty($paramIsNull)) {
                if ($paramIsNull == 1) {
                    $loop++;
                    if ($loop == 1) {
                        $isFilter = true;
                    }
                    array_push($filterCollection, '"' . $alias . '" IS NULL');
                }
            }

            /**
             * Is NOT NULL
             */
            $paramIsNull = $this->request->all($alias . '_not_null', 2);
            if (!empty($paramIsNull)) {
                if ($paramIsNull == 1) {
                    $loop++;
                    if ($loop == 1) {
                        $isFilter = true;
                    }
                    array_push($filterCollection, '"' . $alias . '" IS NOT NULL');
                }
            }


            /**
             * Equal
             */
            // if (!empty($this->request->all($alias))) {
            //     $loop++;
            //     if ($loop == 1) {
            //         $isFilter = true;
            //     }
            //     var_dump($this->request->all($alias));
            //     var_dump($filterCollection);
            //     var_dump($alias);
            //     var_dump($this->request->all($alias));
                
            //     array_push($filterCollection, '"' . $alias . '"' . " = '" . $this->request->all($alias) . "'");
            // }

            /**
             * Not Equal
             */
            // if (!empty($this->request->all($alias . '_not'))) {
            //     $loop++;
            //     if ($loop == 1) {
            //         $isFilter = true;
            //     }
            //     array_push($filterCollection, '"' . $alias . '"' . " != '" . $this->request->all($alias) . "'");
            // }

            /**
             * IN
             */
            if (!empty($this->request->all($alias . '_in'))) {
                $loop++;
                if ($loop == 1) {
                    $isFilter = true;
                }
                $inCollection = $this->filterIn($alias, $this->request->all($alias . '_in'));
                array_push($filterCollection, $inCollection);
            }

            /**
             * Not IN
             */
            if (!empty($this->request->all($alias . '_not_in'))) {
                $loop++;
                if ($loop == 1) {
                    $isFilter = true;
                }
                $inCollection = $this->filterIn($alias, $this->request->all($alias . '_not_in'), null, true);
                array_push($filterCollection, $inCollection);
            }

            /**
             * ILIKE
             */
            // if (!empty($this->request->all($alias . '_like'))) {
            //     $loop++;
            //     if ($loop == 1) {
            //         $isFilter = true;
            //     }
            //     array_push($filterCollection, '"' . $alias . '"' . " ILIKE '%" . $this->request->all($alias . '_like') . "%'");
            // }

            /**
             * Greater Than Equal
             */
            // if (!empty($this->request->all($alias . '_gte'))) {
            //     $loop++;
            //     if ($loop == 1) {
            //         $isFilter = true;
            //     }
            //     array_push($filterCollection, '"' . $alias . '"' . " >= '" . $this->request->all($alias . '_gte') . "'");
            // }

            /**
             * Greater Than
             */
            // if (!empty($this->request->all($alias . '_gt'))) {
            //     $loop++;
            //     if ($loop == 1) {
            //         $isFilter = true;
            //     }
            //     array_push($filterCollection, '"' . $alias . '"' . " > '" . $this->request->all($alias . '_gt') . "'");
            // }

            /**
             * Less Than Equal
             */
            // if (!empty($this->request->all($alias . '_lte'))) {
            //     $loop++;
            //     if ($loop == 1) {
            //         $isFilter = true;
            //     }
            //     array_push($filterCollection, '"' . $alias . '"' . " <= '" . $this->request->all($alias . '_lte') . "'");
            // }

            /**
             * Less Than
             */
            // if (!empty($this->request->all($alias . '_lt'))) {
            //     $loop++;
            //     if ($loop == 1) {
            //         $isFilter = true;
            //     }
            //     array_push($filterCollection, '"' . $alias . '"' . " < '" . $this->request->all($alias . '_lt') . "'");
            // }
        }
        if ($isFilter) {
            return ' AND ' . implode(' AND ', $filterCollection);
        } else {
            return null;
        }
    }

    /**
     * @param string $column
     * @param $input
     * @param null $initial
     * @param false $not
     * @return string|null
     */
    public function filterIn(string $column, $input, $initial = null, $not = false)
    {
        if (is_array($input)) {
            if (!reset($input)) {
                return null;
            } else {
                $stateArray = array();
                foreach ($input as $key_field => $data_field) {
                    array_push($stateArray, "'" . $data_field . "'");
                }
                $implodeArray = implode(',', $stateArray);
                if ($not) {
                    if (!empty($initial)) {
                        return '' . $initial . '."' . $column . '" NOT IN (' . $implodeArray . ')';
                    } else if (empty($initial)) {
                        return '"' . $column . '" NOT IN (' . $implodeArray . ')';
                    }
                } else {
                    if (!empty($initial)) {
                        return '' . $initial . '."' . $column . '" IN (' . $implodeArray . ')';
                    } else if (empty($initial)) {
                        return '"' . $column . '" IN (' . $implodeArray . ')';
                    }
                }
            }
        }
        if (!is_array($input)) {
            return null;
        }
    }

    /**
     * @param $fetch
     * @param int $page
     * @param int $perPage
     * @param bool $toString
     * @return array
     */
    private function resultSerialize($fetch, int $page, int $perPage, bool $toString = false): array
    {
        if ($toString) {
            foreach ($fetch as $key => $data) {
                foreach ($data as $property => $datum) {
                    $data->$property = (string)$datum;
                }
            }
        }

        /**
         * Simpan total row
         */
        $totalRow = array();
        if (!empty($fetch)) {
            array_push($totalRow, $fetch[0]->total_row);
        } else {
            array_push($totalRow, 0);
        }
        /**
         * Hapus total_row dari koleksi
         */
        foreach ($fetch as $index => $key) {
            unset($fetch[$index]->total_row);
        }

        return [
            'status' => true,
            'data' => $fetch,
            'meta' => [
                "pagination" => [
                    "total" => (int)$totalRow[0],
                    "count" => (int)count($fetch),
                    "per_page" => (int)$perPage,
                    "current_page" => (int)$page,
                    'total_page' => ceil((int)$totalRow[0] / (int)$perPage)
                ],
            ]
        ];
    }

    /**
     * @param string $on
     * @return string
     */
    public function distinct(string $on): string
    {
        $explodeOn = explode('.', $on);
        $jumlahArrayExplodeOn = count($explodeOn);
        if ($jumlahArrayExplodeOn > 1) {
            if ($explodeOn[0] == '*') {
                unset($explodeOn[0]);
                return sprintf('DISTINCT ON(%s)', trim(implode('.', $explodeOn)));
            } else {
                return sprintf('DISTINCT ON(%s)', trim($explodeOn[0] . '."' . $explodeOn[1] . '"'));
            }
        } else {
            return sprintf('DISTINCT ON(%s)', trim($explodeOn[0]));
        }
    }

    /**
     * @param array $select
     * @return object
     */
    public function alias(array $select): object
    {
        /**
         * Set Limit
         */
        $limit = $this->request->all('limit', null);
        if (!empty($limit)) {
            $arrayLimit = explode(',', $limit);
        }
        if (!empty($arrayLimit)) {
            foreach ($arrayLimit as $keyLimit => $dataLimit) {
                $searchKey = array_search($dataLimit, $select);
                if ($searchKey) {
                    unset($select[$searchKey]);
                }
            }
        }

        /**
         * Generate Select Alias
         */
        $mainSelect = array();
        $subSelect = array();
        foreach ($select as $key => $alias) {
            /**
             * Main
             */
            $explodeKey = explode('.', $key);
            $jumlahArrayExplodeKey = count($explodeKey);
            if ($jumlahArrayExplodeKey > 1) {
                if ($explodeKey[0] == '*') {
                    unset($explodeKey[0]);
                    array_push($mainSelect, implode('.', $explodeKey) . ' AS "' . $alias . '"');
                } else {
                    array_push($mainSelect, $explodeKey[0] . '."' . $explodeKey[1] . '" AS "' . $alias . '"');
                }
            } else {
                array_push($mainSelect, '"' . $key . '" AS "' . $alias . '"');
            }

            /**
             * Sub
             */
            array_push($subSelect, '"' . $alias . '"');
        }
        return (object)[
            'main' => implode(',', $mainSelect),
            'sub' => implode(',', $subSelect),
        ];
    }

    /**
     * @param $path
     * @param $expireTime
     * @return null
     */
    public function storageGet($path, $expireTime)
    {
        return !empty($path) ? storage()->disk('minio-public')->temporaryUrl(trim($path), $expireTime) : null;
    }

    /**
     * @param null $inp
     * @return array
     */
}
