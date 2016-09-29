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
-- Table structure for table `WFGROWTHMODEL`
--

CREATE TABLE IF NOT EXISTS `WFGROWTHMODEL` (
  `CATEGORY` text NOT NULL,
  `DELTA` text NOT NULL,
  `SLOPE` float NOT NULL,
  `INTERCEPT` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `WFGROWTHMODEL`
--

INSERT INTO `WFGROWTHMODEL` (`CATEGORY`, `DELTA`, `SLOPE`, `INTERCEPT`) VALUES
('cust_demographics_ai', 'normal_tot_bal', 7.71869, -37.2909),
('cust_demographics_ai', 'typeA_ct', -0.0729532, 0.387545),
('cust_demographics_ai', 'typeB_ct', -0.0664466, 0.346753),
('cust_demographics_ai', 'typeC_flag', -0.0245353, 0.0365937),
('cust_demographics_ai', 'typeD_flag', -0.0183357, 0.0352017),
('cust_demographics_ai', 'typeE_flag', -0.00705087, 0.00668201),
('cust_demographics_ai', 'typeF_flag', -0.0871803, 0.270648),
('cust_demographics_ai', 'typeG_flag', -0.00306685, -0.0489487),
('cust_demographics_aii', 'normal_tot_bal', 9.32693, -41.9965),
('cust_demographics_aii', 'typeA_ct', -0.0295386, 0.256408),
('cust_demographics_aii', 'typeB_ct', -0.051255, 0.300269),
('cust_demographics_aii', 'typeC_flag', -0.0314201, 0.0568625),
('cust_demographics_aii', 'typeD_flag', -0.0141228, 0.022312),
('cust_demographics_aii', 'typeE_flag', -0.0172919, 0.0372614),
('cust_demographics_aii', 'typeF_flag', -0.0958387, 0.295316),
('cust_demographics_aii', 'typeG_flag', -0.0132901, -0.0183643),
('typeA_bal_cat', 'normal_tot_bal', 4.83337, -26.7642),
('typeA_bal_cat', 'typeA_ct', -0.0425025, 0.279688),
('typeA_bal_cat', 'typeB_ct', -0.0163097, 0.189601),
('typeA_bal_cat', 'typeC_flag', -0.0110992, -0.00808281),
('typeA_bal_cat', 'typeD_flag', -0.00740824, -0.000517015),
('typeA_bal_cat', 'typeE_flag', -0.0156989, 0.0267412),
('typeA_bal_cat', 'typeF_flag', -0.0400387, 0.11348),
('typeA_bal_cat', 'typeG_flag', -0.00257151, -0.051419),
('typeB_bal_cat', 'normal_tot_bal', -17.7669, 39.206),
('typeB_bal_cat', 'typeA_ct', -0.01029, 0.198756),
('typeB_bal_cat', 'typeB_ct', -0.0347911, 0.250999),
('typeB_bal_cat', 'typeC_flag', 0.0140326, -0.0793374),
('typeB_bal_cat', 'typeD_flag', 0.00587948, -0.0376246),
('typeB_bal_cat', 'typeE_flag', -0.00319576, -0.00496572),
('typeB_bal_cat', 'typeF_flag', -0.00952611, 0.0367376),
('typeB_bal_cat', 'typeG_flag', 0.0031099, -0.0675042),
('typeC_bal_cat', 'normal_tot_bal', -18.2256, -6.52019),
('typeC_bal_cat', 'typeA_ct', 0.0236704, 0.158127),
('typeC_bal_cat', 'typeB_ct', 0.0160362, 0.14008),
('typeC_bal_cat', 'typeC_flag', -0.227772, 0.0568653),
('typeC_bal_cat', 'typeD_flag', -0.00872245, -0.016395),
('typeC_bal_cat', 'typeE_flag', -0.0218863, -0.00549984),
('typeC_bal_cat', 'typeF_flag', -0.0248141, 0.0184375),
('typeC_bal_cat', 'typeG_flag', -0.00915589, -0.0543977),
('typeD_bal_cat', 'normal_tot_bal', 2.58863, -14.417),
('typeD_bal_cat', 'typeA_ct', -0.0108279, 0.169433),
('typeD_bal_cat', 'typeB_ct', 0.0034348, 0.146225),
('typeD_bal_cat', 'typeC_flag', -0.0680017, -0.0277061),
('typeD_bal_cat', 'typeD_flag', -0.289839, 0.0207751),
('typeD_bal_cat', 'typeE_flag', -0.0432044, -0.00846738),
('typeD_bal_cat', 'typeF_flag', -0.0521775, 0.0155222),
('typeD_bal_cat', 'typeG_flag', -0.0238307, -0.0548293),
('typeE_bal_cat', 'normal_tot_bal', -5.02732, -13.4529),
('typeE_bal_cat', 'typeA_ct', 0.00527435, 0.167281),
('typeE_bal_cat', 'typeB_ct', 0.000505398, 0.146648),
('typeE_bal_cat', 'typeC_flag', -0.00881688, -0.0362205),
('typeE_bal_cat', 'typeD_flag', 0.0185181, -0.0222101),
('typeE_bal_cat', 'typeE_flag', -0.489683, 0.043896),
('typeE_bal_cat', 'typeF_flag', 0.0427785, 0.0030764),
('typeE_bal_cat', 'typeG_flag', -0.00936445, -0.0570642),
('cust_outreach_ai', 'normal_tot_bal', 1.42057, -15.6999),
('cust_outreach_ai', 'typeA_ct', -0.0150468, 0.185356),
('cust_outreach_ai', 'typeB_ct', 0.00212085, 0.144249),
('cust_outreach_ai', 'typeC_flag', -0.00522344, -0.0312166),
('cust_outreach_ai', 'typeD_flag', 0.00789036, -0.0291482),
('cust_outreach_ai', 'typeE_flag', -0.00327075, -0.0107533),
('cust_outreach_ai', 'typeF_flag', 0.0410525, -0.0394152),
('cust_outreach_ai', 'typeG_flag', 0.00163149, -0.0600734),
('cust_outreach_aii', 'normal_tot_bal', 1.4152, -15.578),
('cust_outreach_aii', 'typeA_ct', 0.00875376, 0.158476),
('cust_outreach_aii', 'typeB_ct', 0.00723394, 0.138912),
('cust_outreach_aii', 'typeC_flag', -0.00139039, -0.0357743),
('cust_outreach_aii', 'typeD_flag', -0.0037247, -0.0159859),
('cust_outreach_aii', 'typeE_flag', -0.00393243, -0.0103074),
('cust_outreach_aii', 'typeF_flag', 0.00585127, 0.00187585),
('cust_outreach_aii', 'typeG_flag', -0.0119045, -0.0453522),
('cust_outreach_aiii', 'normal_tot_bal', 1.79424, -15.9455),
('cust_outreach_aiii', 'typeA_ct', -0.00779092, 0.176128),
('cust_outreach_aiii', 'typeB_ct', 0.00553934, 0.140865),
('cust_outreach_aiii', 'typeC_flag', 0.000547979, -0.0378508),
('cust_outreach_aiii', 'typeD_flag', 0.00809214, -0.028536),
('cust_outreach_aiii', 'typeE_flag', -0.0114833, -0.00243234),
('cust_outreach_aiii', 'typeF_flag', 0.0578781, -0.0528706),
('cust_outreach_aiii', 'typeG_flag', -0.0118165, -0.0457172),
('cust_outreach_aiv', 'normal_tot_bal', 5.67409, -14.9384),
('cust_outreach_aiv', 'typeA_ct', -0.0454329, 0.175001),
('cust_outreach_aiv', 'typeB_ct', -0.0632413, 0.156578),
('cust_outreach_aiv', 'typeC_flag', -0.0136637, -0.0351403),
('cust_outreach_aiv', 'typeD_flag', -0.0207913, -0.0167552),
('cust_outreach_aiv', 'typeE_flag', -0.0170819, -0.0118796),
('cust_outreach_aiv', 'typeF_flag', 0.0963395, -0.00685327),
('cust_outreach_aiv', 'typeG_flag', -0.00541403, -0.0573369),
('cust_outreach_av', 'normal_tot_bal', 0.501131, -15.0858),
('cust_outreach_av', 'typeA_ct', 0.00483953, 0.157935),
('cust_outreach_av', 'typeB_ct', 0.0050105, 0.13638),
('cust_outreach_av', 'typeC_flag', 0.00225098, -0.0419126),
('cust_outreach_av', 'typeD_flag', 0.0019804, -0.0240821),
('cust_outreach_av', 'typeE_flag', 0.00115066, -0.0169173),
('cust_outreach_av', 'typeF_flag', 0.0136575, -0.01997),
('cust_outreach_av', 'typeG_flag', -0.000885464, -0.0563566),
('cust_outreach_avi', 'normal_tot_bal', -0.84848, -7.74462),
('cust_outreach_avi', 'typeA_ct', 0.000966526, 0.160724),
('cust_outreach_avi', 'typeB_ct', 0.0019807, 0.131982),
('cust_outreach_avi', 'typeC_flag', 0.0009718, -0.0444978),
('cust_outreach_avi', 'typeD_flag', 0.000186646, -0.0213877),
('cust_outreach_avi', 'typeE_flag', 0.0011823, -0.0233355),
('cust_outreach_avi', 'typeF_flag', 0.00535583, -0.0316374),
('cust_outreach_avi', 'typeG_flag', -0.00265706, -0.0384272),
('cust_outreach_avii', 'normal_tot_bal', -7.45299, -10.0083),
('cust_outreach_avii', 'typeA_ct', 0.00200819, 0.16682),
('cust_outreach_avii', 'typeB_ct', -0.000163897, 0.146797),
('cust_outreach_avii', 'typeC_flag', 0.00230296, -0.0385225),
('cust_outreach_avii', 'typeD_flag', 0.00197657, -0.0210726),
('cust_outreach_avii', 'typeE_flag', -0.000078946, -0.0145026),
('cust_outreach_avii', 'typeF_flag', -0.0201377, 0.0191102),
('cust_outreach_avii', 'typeG_flag', 0.00590845, -0.0613882),
('cust_outreach_aviii', 'normal_tot_bal', 0.749534, -14.1869),
('cust_outreach_aviii', 'typeA_ct', 0.0181436, 0.164666),
('cust_outreach_aviii', 'typeB_ct', 0.0138181, 0.144237),
('cust_outreach_aviii', 'typeC_flag', -0.00549819, -0.0362896),
('cust_outreach_aviii', 'typeD_flag', 0.0024191, -0.0204326),
('cust_outreach_aviii', 'typeE_flag', 0.004008, -0.0152622),
('cust_outreach_aviii', 'typeF_flag', -0.0161681, 0.011073),
('cust_outreach_aviii', 'typeG_flag', 0.00448891, -0.0589845),
('wf_outreach_flag_chan_i', 'normal_tot_bal', -63.7581, 2.29182),
('wf_outreach_flag_chan_i', 'typeA_ct', 0.0564004, 0.153452),
('wf_outreach_flag_chan_i', 'typeB_ct', 0.0435203, 0.135551),
('wf_outreach_flag_chan_i', 'typeC_flag', -0.402905, 0.0660139),
('wf_outreach_flag_chan_i', 'typeD_flag', -0.0112551, -0.0171147),
('wf_outreach_flag_chan_i', 'typeE_flag', 0.0195597, -0.0195597),
('wf_outreach_flag_chan_i', 'typeF_flag', 0.217895, -0.0476767),
('wf_outreach_flag_chan_i', 'typeG_flag', -0.0982034, -0.0330069),
('wf_outreach_flag_chan_ii', 'normal_tot_bal', -25.9898, 0.165958),
('wf_outreach_flag_chan_ii', 'typeA_ct', 0.0078986, 0.163589),
('wf_outreach_flag_chan_ii', 'typeB_ct', 0.029649, 0.130487),
('wf_outreach_flag_chan_ii', 'typeC_flag', -0.00575752, -0.0341228),
('wf_outreach_flag_chan_ii', 'typeD_flag', 0.0221455, -0.0321156),
('wf_outreach_flag_chan_ii', 'typeE_flag', 0.0101022, -0.0200723),
('wf_outreach_flag_chan_ii', 'typeF_flag', 0.264441, -0.136491),
('wf_outreach_flag_chan_ii', 'typeG_flag', -0.0219627, -0.0461662),
('wf_outreach_flag_chan_iii', 'normal_tot_bal', 0, -14.0529),
('wf_outreach_flag_chan_iii', 'typeA_ct', 0, 0.16791),
('wf_outreach_flag_chan_iii', 'typeB_ct', 0, 0.146708),
('wf_outreach_flag_chan_iii', 'typeC_flag', 0, -0.0372727),
('wf_outreach_flag_chan_iii', 'typeD_flag', 0, -0.02),
('wf_outreach_flag_chan_iii', 'typeE_flag', 0, -0.0145455),
('wf_outreach_flag_chan_iii', 'typeF_flag', 0, 0.00818182),
('wf_outreach_flag_chan_iii', 'typeG_flag', 0, -0.0581818),
('wf_outreach_flag_chan_iv', 'normal_tot_bal', 23.4451, -17.4658),
('wf_outreach_flag_chan_iv', 'typeA_ct', -0.0879806, 0.180718),
('wf_outreach_flag_chan_iv', 'typeB_ct', -0.113331, 0.163206),
('wf_outreach_flag_chan_iv', 'typeC_flag', -0.0660105, -0.0276634),
('wf_outreach_flag_chan_iv', 'typeD_flag', 0.00878969, -0.0212795),
('wf_outreach_flag_chan_iv', 'typeE_flag', 0.0243325, -0.0180876),
('wf_outreach_flag_chan_iv', 'typeF_flag', -0.0315025, 0.0127677),
('wf_outreach_flag_chan_iv', 'typeG_flag', -0.041539, -0.0521349);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
