<?php

namespace App\Traits;

use App\Models\TrackSql;

trait Track
{

    // public static function insertSql($table, $values)
    // {
    // $table = $table->getTable();
    // $values = self::stringValues($values);
    // $str = "INSERT INTO $table ($values[key]) VALUES ($values[val])";
    // return static::createTrack($str);

    // }

    public static function insertSql($table, $values)
    {
        $table = $table->getTable();

        // cek apakah array of rows (multi insert) atau single row
        $isMulti = isset($values[0]) && is_array($values[0]);

        if ($isMulti) {
            // multi insert
            $keys = implode(", ", array_keys($values[0]));

            $valuesArr = [];
            foreach ($values as $row) {
                $vals = array_map(function ($v) {
                    return is_numeric($v) ? $v : "'".addslashes($v)."'";
                }, array_values($row));
                $valuesArr[] = "(".implode(", ", $vals).")";
            }

            $valStr = implode(", ", $valuesArr);
            $str = "INSERT INTO $table ($keys) VALUES $valStr";
        } else {
            // single insert
            $keys = implode(", ", array_keys($values));

            $vals = array_map(function ($v) {
                return is_numeric($v) ? $v : "'".addslashes($v)."'";
            }, array_values($values));

            $valStr = implode(", ", $vals);
            $str = "INSERT INTO $table ($keys) VALUES ($valStr)";
        }

        return static::createTrack($str);
    }


    public static function stringValues($values)
    {

        $count = 1;
        $stringVal = '';
        $stringKeys = [];

        foreach ($values as $val) {
            if ($count < count($values)) {
                $and = ',';
                $count++;
            } else {
                $and = '';
            }
            $stringVal .= "'$val'$and";
        }
        $stringKeys = implode(', ', array_keys($values));

        return [
            'val' => $stringVal,
            'key' => $stringKeys
        ];
    }
    public static function stringClause($clause)
    {
        $count = 1;
        $stringClause = '';
        foreach ($clause as $cls) {
            if ($count < count($clause)) {
                $and = ' AND ';
                $count++;
            } else {
                $and = '';
            }
            $keyClasue = array_keys($clause, $cls, true);
            $stringClause .= "$keyClasue[0]".'='."'$cls'".$and;
        }

        return $stringClause;
    }

    public static function deleteSql($table, $clause)
    {
        $table = $table->getTable();
        $stringClause = self::stringClause($clause);
        $str = "DELETE FROM $table WHERE $stringClause";
        return static::createTrack($str);
    }
    public static function updateSql($table, $values, $clause)
    {
        $table = $table->getTable();
        $keys = [];
        foreach ($values as $vals => $index) {
            $keys[] = "$vals='$index'";
        }
        $stringKeys = implode(",", $keys);
        $stringClause = self::stringClause($clause);
        $str = "UPDATE $table SET $stringKeys WHERE $stringClause";
        return static::createTrack($str);
    }

    protected static function createTrack($sql)
    {
        $data = [
            'tanggal' => date('Y-m-d H:i:s'),
            'sqle' => $sql,
            'usere' => session()->get('pegawai')->nik,
        ];
        try {
            $result = TrackSql::create($data);
        } catch (\Illuminate\Database\QueryException $e) {
            $result = $e->errorInfo;
        }

        return $result;
    }
}
