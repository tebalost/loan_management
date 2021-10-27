<?php
	include_once('../data/segment-builder.php');
	class BureauSegments{

		private function setDataString(){
			$segmentBuilder =  new BureauSegmentBuilder();
			return $segmentBuilder->getBureauDataTobeSubmitted();
		}
		
		
		public function getDataString(){
			return $this->setDataString();
    	}
		
		/* dealing with why the data to be formatted and viewed */
		public function getDataToBeFormatted(){
			return $this->setDataToBeFormatted();
		}
		
		private function setDataToBeFormatted(){
			$segmentBuilder =  new BureauSegmentBuilder();
			return $segmentBuilder->getBureauDataToBeFormatted();
		}
	}




?>