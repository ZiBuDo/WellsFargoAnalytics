-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 27, 2016 at 05:32 PM
-- Server version: 5.5.52-0ubuntu0.14.04.1-log
-- PHP Version: 5.5.9-1ubuntu4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `MindSumo`
--

-- --------------------------------------------------------

--
-- Table structure for table `WF1VARSTATS`
--

CREATE TABLE IF NOT EXISTS `WF1VARSTATS` (
  `VAR` text NOT NULL,
  `STAT` text NOT NULL,
  `VALUE` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `WF1VARSTATS`
--

INSERT INTO `WF1VARSTATS` (`VAR`, `STAT`, `VALUE`) VALUES
('normal_tot_bal', 'SUM', -0.00542251),
('normal_tot_bal', 'MEAN', -0.0000000451876),
('normal_tot_bal', 'VARIANCE', 1),
('normal_tot_bal', 'STDDEV', 1),
('cust_demographics_ai', 'SUM', 361558),
('cust_demographics_ai', 'MEAN', 1.50649),
('cust_demographics_ai', 'VARIANCE', 2.2199),
('cust_demographics_ai', 'STDDEV', 1.48993),
('cust_demographics_aii', 'SUM', 360126),
('cust_demographics_aii', 'MEAN', 1.00035),
('cust_demographics_aii', 'VARIANCE', 2.00053),
('cust_demographics_aii', 'STDDEV', 1.4144),
('typeA_ct', 'SUM', 163873),
('typeA_ct', 'MEAN', 0.341402),
('typeA_ct', 'VARIANCE', 0.49683),
('typeA_ct', 'STDDEV', 0.704862),
('typeB_ct', 'SUM', 195381),
('typeB_ct', 'MEAN', 0.325635),
('typeB_ct', 'VARIANCE', 0.567446),
('typeB_ct', 'STDDEV', 0.75329),
('typeC_flag', 'SUM', 16500),
('typeC_flag', 'MEAN', 0.0229167),
('typeC_flag', 'VARIANCE', 0.0219538),
('typeC_flag', 'STDDEV', 0.148168),
('typeD_flag', 'SUM', 6769),
('typeD_flag', 'MEAN', 0.00805833),
('typeD_flag', 'VARIANCE', 0.00793774),
('typeD_flag', 'STDDEV', 0.089094),
('typeE_flag', 'SUM', 4765),
('typeE_flag', 'MEAN', 0.00496354),
('typeE_flag', 'VARIANCE', 0.00491735),
('typeE_flag', 'STDDEV', 0.0701238),
('typeF_flag', 'SUM', 48544),
('typeF_flag', 'MEAN', 0.0449481),
('typeF_flag', 'VARIANCE', 0.041132),
('typeF_flag', 'STDDEV', 0.20281),
('typeG_flag', 'SUM', 2623),
('typeG_flag', 'MEAN', 0.00218583),
('typeG_flag', 'VARIANCE', 0.00217676),
('typeG_flag', 'STDDEV', 0.0466557),
('typeA_bal_cat', 'SUM', 315871),
('typeA_bal_cat', 'MEAN', 0.239296),
('typeA_bal_cat', 'VARIANCE', 0.768098),
('typeA_bal_cat', 'STDDEV', 0.876412),
('typeB_bal_cat', 'SUM', 360007),
('typeB_bal_cat', 'MEAN', 0.250005),
('typeB_bal_cat', 'VARIANCE', 0.796885),
('typeB_bal_cat', 'STDDEV', 0.892684),
('typeC_bal_cat', 'SUM', 49500),
('typeC_bal_cat', 'MEAN', 0.0317308),
('typeC_bal_cat', 'VARIANCE', 0.11441),
('typeC_bal_cat', 'STDDEV', 0.338245),
('typeD_bal_cat', 'SUM', 16840),
('typeD_bal_cat', 'MEAN', 0.0100238),
('typeD_bal_cat', 'VARIANCE', 0.0338074),
('typeD_bal_cat', 'STDDEV', 0.183868),
('typeE_bal_cat', 'SUM', 14295),
('typeE_bal_cat', 'MEAN', 0.00794167),
('typeE_bal_cat', 'VARIANCE', 0.0289975),
('typeE_bal_cat', 'STDDEV', 0.170287),
('cust_outreach_ai', 'SUM', 151453),
('cust_outreach_ai', 'MEAN', 0.0788818),
('cust_outreach_ai', 'VARIANCE', 0.450602),
('cust_outreach_ai', 'STDDEV', 0.671269),
('cust_outreach_aii', 'SUM', 129079),
('cust_outreach_aii', 'MEAN', 0.063274),
('cust_outreach_aii', 'VARIANCE', 0.435669),
('cust_outreach_aii', 'STDDEV', 0.660052),
('cust_outreach_aiii', 'SUM', 126458),
('cust_outreach_aiii', 'MEAN', 0.0585454),
('cust_outreach_aiii', 'VARIANCE', 0.26229),
('cust_outreach_aiii', 'STDDEV', 0.512142),
('cust_outreach_aiv', 'SUM', 18618),
('cust_outreach_aiv', 'MEAN', 0.00816579),
('cust_outreach_aiv', 'VARIANCE', 0.0276184),
('cust_outreach_aiv', 'STDDEV', 0.166188),
('cust_outreach_av', 'SUM', 270834),
('cust_outreach_av', 'MEAN', 0.112847),
('cust_outreach_av', 'VARIANCE', 2.8568),
('cust_outreach_av', 'STDDEV', 1.69021),
('cust_outreach_avi', 'SUM', 920690),
('cust_outreach_avi', 'MEAN', 0.365353),
('cust_outreach_avi', 'VARIANCE', 13.4698),
('cust_outreach_avi', 'STDDEV', 3.67012),
('cust_outreach_avii', 'SUM', 75390),
('cust_outreach_avii', 'MEAN', 0.0285568),
('cust_outreach_avii', 'VARIANCE', 0.21415),
('cust_outreach_avii', 'STDDEV', 0.462763),
('cust_outreach_aviii', 'SUM', 24552),
('cust_outreach_aviii', 'MEAN', 0.00889565),
('cust_outreach_aviii', 'VARIANCE', 0.0584423),
('cust_outreach_aviii', 'STDDEV', 0.241748),
('wf_outreach_flag_chan_i', 'SUM', 31268),
('wf_outreach_flag_chan_i', 'MEAN', 0.0108569),
('wf_outreach_flag_chan_i', 'VARIANCE', 0.0106261),
('wf_outreach_flag_chan_i', 'STDDEV', 0.103083),
('wf_outreach_flag_chan_ii', 'SUM', 65628),
('wf_outreach_flag_chan_ii', 'MEAN', 0.021876),
('wf_outreach_flag_chan_ii', 'VARIANCE', 0.020938),
('wf_outreach_flag_chan_ii', 'STDDEV', 0.1447),
('wf_outreach_flag_chan_iii', 'SUM', 0),
('wf_outreach_flag_chan_iii', 'MEAN', 0),
('wf_outreach_flag_chan_iii', 'VARIANCE', 0),
('wf_outreach_flag_chan_iii', 'STDDEV', 0),
('wf_outreach_flag_chan_iv', 'SUM', 17728),
('wf_outreach_flag_chan_iv', 'MEAN', 0.0054716),
('wf_outreach_flag_chan_iv', 'VARIANCE', 0.00541284),
('wf_outreach_flag_chan_iv', 'STDDEV', 0.073572);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
