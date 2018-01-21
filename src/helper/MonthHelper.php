<?php
namespace Helper;

class MonthHelper 
{
	public function countDominantMonth($awal, $akhir)
	{
		$timeAwal = strtotime($awal);
		$timeAkhir = strtotime($akhir);
		$awal = date_create($awal);
		$akhir = date_create($akhir);
		$nDayAwal = date_diff($awal, date_create(date('Y-m-t', $timeAwal)))->days;
		$nDayAkhir = date_diff(date_create(date('Y-m-1', $timeAkhir)), $akhir)->days;
		$periode = $nDayAwal > $nDayAkhir ? date('Y', $timeAwal).date('m', $timeAwal) : date('Y', $timeAkhir).date('m', $timeAkhir);
		return $periode;
	}

	public function getMonthList()
	{
		$monthIndex = range(1, 12);
		$monthName = array_map(
				function($i){
					return date('F', mktime(0,0,0,$i,1));
				}, $monthIndex);
		$monthIndexWithName = array_combine($monthIndex, $monthName);
		return $monthIndexWithName;
	}

	public function getYearList($startYear = 2017)
	{
		$yearList = range(date('Y'), $startYear);
		return $yearList;
	}

	public function createPeriode($month, $year)
	{
		$year = isset($year) ? $year : date('Y');
		$month = isset($month) ? str_pad($month, 2, '0', STR_PAD_LEFT) : date('m');
		$periode = $year.$month;

		return $periode;
	}

	public function getTodayDate()
	{
		return date('d M Y');
	}
}
