-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th8 20, 2026 lúc 12:49 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `db_camau_vanhoa`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `articles`
--

CREATE TABLE `articles` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `views` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) DEFAULT 'approved'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `articles`
--

INSERT INTO `articles` (`id`, `category_id`, `title`, `content`, `image`, `views`, `created_at`, `status`) VALUES
(1, 1, 'Lịch sử hình thành vùng đất Cà Mau', 'Nội dung tóm tắt về lịch sử hình thành vùng đất Mũi Cà Mau...', 'assets/img/lichsu.jpg', 101, '2026-08-18 14:47:16', 'approved'),
(2, 2, 'Khám phá Rừng Quốc gia U Minh Hạ', 'Nội dung giới thiệu về hệ sinh thái rừng tràm U Minh Hạ...', 'assets/img/uminh.jpg', 22, '2026-08-18 14:47:16', 'approved'),
(3, 4, 'Giai thoại Bác Ba Phi - Bắt rùa khổng lồ', 'Những câu chuyện cười dân gian đặc sắc mang đậm dấu ấn Cà Mau...', 'assets/img/bacbaphi.jpg', 9, '2026-08-18 14:47:16', 'approved'),
(8, 2, 'Cua Cà Mau liệu có như lời đồn?', 'Cua Cà Mau từ lâu đã được ví như \"vua của các loại hải sản\" đất Nam Bộ. Nhưng giữa một thị trường thật giả lẫn lộn cùng những lời quảng cáo có phần quá đà, liệu chất lượng của món đặc sản này có thực sự thần thánh như đồn đại?\r\n\r\nNguồn gốc của \"lời đồn\"\r\n\r\nKhông phải tự nhiên mà cua Cà Mau lại mang danh xưng đắt giá đến vậy. Sự khác biệt lớn nhất đến từ môi trường sống:\r\n\r\nHệ sinh thái ngập mặn tự nhiên: Cua được nuôi trồng dưới tán rừng ngập mặn, bãi bồi ven biển với nguồn thức ăn tự nhiên phong phú như cá con, còng, tôm...\r\n\r\nThịt chắc và ngọt đậm: Nhờ di chuyển liên tục trong môi trường nước có độ mặn lý tưởng, thịt cua Cà Mau cực kỳ săn chắc, ngọt đậm đà, không bị bở hay gạch có vị nồng như cua nuôi công nghiệp.\r\n\r\nĐa dạng chủng loại: Từ cua thịt (cua y), cua gạch, đến cua cốm (cua 2 da - loại cua đắt giá và ngon bậc nhất), mỗi loại đều mang một hương vị đặc trưng riêng.\r\n\r\nThực tế trải nghiệm: Có đúng như mong đợi?\r\n\r\nNếu bạn may mắn thưởng thức đúng cua Cà Mau chính gốc, câu trả lời chắc chắn là CÓ.\r\n\r\nThịt cua gỡ ra thành từng thớ dai, ngọt vị biển tự nhiên mà không cần chấm quá nhiều gia vị. Phần gạch cua gạch thì béo ngậy, vàng ươm, ăn không hề bị ngấy. Đặc biệt, cua vừa bắt lên còn sống khỏe, luộc hay hấp sả thôi cũng đủ làm xiêu lòng những thực khách khó tính nhất.\r\n\r\nVì sao nhiều người cảm thấy \"vỡ mộng\"?\r\n\r\nDù ngon thật, nhưng không ít người lại có trải nghiệm thất vọng. Nguyên nhân chủ yếu xuất phát từ các vấn đề:\r\n\r\nCua giả danh Cà Mau: Rất nhiều loại cua trôi nổi, cua nuôi hồ công nghiệp từ các nơi khác được gắn mác \"Cà Mau\" để bán giá cao.\r\n\r\nBẫy dây buộc nặng: Chiêu trò xài dây vải/dây dù thấm nước nặng hàng trăm gam để độn trọng lượng khiến người mua ấm ức vì \"tiền thịt ít, tiền dây nhiều\".\r\n\r\nCua bị ốp (rỗng thịt): Mua phải cua thu hoạch sai thời điểm (như lúc cua mới lột vỏ) khiến thịt bị bở, gạch lỏng lẻo.\r\n\r\nCua Cà Mau hoàn toàn xứng danh \"như lời đồn\" về độ ngon, chắc và thơm béo. Tuy nhiên, chất lượng thực tế phụ thuộc rất lớn vào việc bạn có chọn đúng nơi bán uy tín và biết cách chọn cua hay không.', 'assets/img/6b5b70ac11c76d8e5974549bf0807d02.jpg', 2, '2026-08-20 09:25:17', 'approved'),
(9, 2, 'những địa điểm nên đi nếu đã đến cà mau', 'Đến với Cà Mau – mảnh đất cực Nam của Tổ quốc, bạn sẽ được hòa mình vào không gian sông nước hữu tình, những cánh rừng ngập mặn bạt ngàn và nét văn hóa sông nước miền Tây vô cùng đặc trưng. Nếu đang lên kế hoạch du lịch Cà Mau, đây là những địa điểm nổi tiếng bạn nhất định không nên bỏ lỡ.\r\n\r\nMũi Cà Mau & Cột Mốc Tọa Độ Quốc Gia (Đất Mũi)\r\n\r\nĐịa điểm biểu tượng mà bất kỳ ai khi đặt chân đến Cà Mau cũng muốn ghé thăm một lần trong đời.\r\n\r\nĐiểm nổi bật: Nơi đây có Mốc tọa độ quốc gia GPS 0001, Cột cờ Hà Nội tại Đất Mũi và biểu tượng con tàu rẽ sóng hướng ra biển lớn.\r\n\r\nTrải nghiệm: Check-in mốc tọa độ, ngắm hoàng hôn và bình minh tại cùng một điểm trên đất liền, phóng tầm mắt ra biển Tây bao la.\r\n\r\nRừng Quốc Gia U Minh Hạ\r\n\r\nThiên đường xanh dành cho những ai yêu thích thiên nhiên hoang sơ và khám phá sinh thái ngập nước.\r\n\r\nĐiểm nổi bật: Hệ sinh thái rừng tràm ngập nước đặc trưng với vô số loài động thực vật quý hiếm.\r\n\r\nTrải nghiệm: Đi vỏ lãi (thuyền máy) xuyên qua những luồng lách trong rừng tràm, lên đài quan sát ngắm toàn cảnh rừng xanh ngút ngàn, và tham gia trải nghiệm \"gác kèo ăn ong\" lấy mật rồng tự nhiên.\r\n\r\nĐầm Thị Tường\r\n\r\nĐược mệnh danh là \"biển hồ giữa đồng bằng\", đây là đầm nước tự nhiên lớn nhất vùng ĐBSCL.\r\n\r\nĐiểm nổi bật: Không gian sông nước mênh mông, bình dị với những căn nhà chòi dựng trên cọc gỗ giữa đầm.\r\n\r\nTrải nghiệm: Ngồi xuồng máy dạo đầm lúc bình minh hoặc hoàng hôn, tự tay giăng lưới bắt cá và thưởng thức các món hải sản tươi sống ngay tại chòi.\r\n\r\nHòn Đá Bạc\r\n\r\nCụm đảo đẹp kỳ thú với những khối đá granite chất đống tự nhiên có niên đại hàng triệu năm.\r\n\r\nĐiểm nổi bật: Nơi có Sân Tiên, Giếng Tiên, Bàn Tay Đá và Đền thờ Cá Ông – nơi lưu giữ bộ xương cá gồng khổng lồ.\r\n\r\nTrải nghiệm: Đi bộ trên cây cầu cạn nối liền các hòn đảo giữa biển, nghe những câu chuyện huyền thoại kỳ bí và ngắm cảnh biển đảo Tây Nam.\r\n\r\nChợ Nổi Cà Mau & Khu Dịch Vụ Du Lịch Sinh Thái Sông Trẹm\r\n\r\nNơi lưu giữ vẹn nguyên nét sinh hoạt văn hóa sông nước đậm chất Nam Bộ.\r\n\r\nĐiểm nổi bật: Buôn bán nông sản, hoa quả ngay trên ghe thuyền sôi động vào mỗi sáng sớm.\r\n\r\nTrải nghiệm: Thưởng thức tô hủ tiếu hay ly cà phê sáng ngay trên thuyền bồng bềnh, cảm nhận nhịp sống hào sảng của người dân địa phương.', 'assets/img/a8562c88d2c3341076f5188939b7e3a3.jpg', 0, '2026-08-20 09:35:58', 'approved'),
(10, 4, 'Các ngành nghề truyền thống Cà Mau', '1.1 Dệt chiếu - Nghề truyền thống ở Cà Mau phổ biến nhất\r\n\r\nĐịa chỉ làng nghề dệt chiếu: Phường Tân Thành (Thành phố Cà Mau), xã Tân Duyệt (huyện Đầm Dơi), xã Tân Lộc (huyện Thới Bình)\r\n\r\nNhắc đến nghề truyền thống ở Cà Mau, không ai là không nghĩ đến những ngôi làng chuyên dệt chiếu. Tuy bạn có thể dễ dàng tìm thấy rất nhiều làng nghề dệt chiếu trên địa bàn tỉnh thành nhưng 3 địa điểm được nhiều người biết đến nhất đó là ngôi làng tại phường Tân Thành (Thành phố Cà Mau), xã Tân Duyệt (huyện Đầm Dơi) cùng xã Tân Lộc (huyện Thới Bình). Trong đó, chiếu hoa thuộc làng Tân Thành là có tên tuổi nổi tiếng nhất.\r\n\r\nGhé những ngôi làng này tham quan, khám phá, bạn sẽ có cơ hội chiêm ngưỡng tận mắt quy trình mà nhóm thợ thủ công tạo nên chiếc chiếu đủ màu sắc, hoa văn tuyệt đẹp. Từ những loại nguyên liệu như sợi lác, dây đay, dây bố... người làm chiếu đã pha nhuộm chúng thành nhiều màu sắc bắt mắt khác nhau rồi sử dụng chúng tỉ mỉ dệt nên tấm chiếu đã được định hình sẵn họa tiết. Có nhìn thấy người người nhà nhà nơi đây ngồi quây quần dệt chiếu, lấy từng sợi lác đan vào nhau sao cho đúng màu và hình vẽ, bạn mới có thể cảm nhận hết được vẻ đẹp của cái nghề truyền thống lâu đời này.\r\n\r\nTop những nghề truyền thống ở Cà Mau có thể bạn chưa biết 2\r\nDệt chiếu là nghề truyền thống ở Cà Mau khá phổ biến\r\n\r\n1.2 Nghề làm mắm\r\n\r\nĐịa chỉ làng nghề làm mắm: Xã Rạch Gốc (huyện Ngọc Hiển), khu vực huyện U Minh, huyện Thới Bình\r\n\r\nMắm từ lâu đã là món ngon gắn liền với các tỉnh thành miền Tây sông nước, trong đó có Cà Mau. Phát triển với nền ẩm thực địa phương phong phú là Nghề làm mắm ở Cà Mau cùng các ngôi làng có con cháu 2, 3 đời chế biến đặc sản. Nhìn chung, xã Rạch Gốc (huyện Ngọc Hiển) với món ba khía muối được ví von như tinh hoa ẩm thực và khu vực huyện U Minh - nơi có rừng cây tràm bạt ngàn sắc xanh cùng môi trường nước cực kì lý tưởng để nuôi trồng thủy sản là 2 địa điểm có nhiều làng nghề làm mắm nhất.\r\n\r\nMắm có thể chế biến từ nhiều loại nguyên liệu khác nhau như ba khía, tôm, cá sặc, cá lóc... Thế nhưng bí quyết để tạo nên vị ngon khó cưỡng của món ăn này nằm ở công đoạn ướp muối và mang đi ủ. Có dịp nhìn ngắm những người thợ làm mắm với đôi tay thoăn thoắt sơ chế cá, sau đó tẩm ướp muối đều lên tất cả các bề mặt và nhấn chặt vào hũ để đem đi ủ, bạn sẽ không khỏi mê mẩn trước quy trình chế biến món ngon kỳ công cùng độ chuyên nghiệp của cư dân kiếm sống bằng nghề truyền thống này.\r\n\r\n1.3 Nghề đan lát\r\n\r\nĐịa chỉ làng nghề đan lát: Xã Biển Bạch, Biển Bạch Đông (huyện Thới Bình), xã Nguyễn Phích (huyện U Minh), xã Phước Quới (huyện Châu Thành)\r\n\r\nĐan lát là nghề truyền thống ở Cà Mau tiếp theo có mặt trong danh sách này. Không chỉ là cái nghề gắn bó sâu sắc với lịch sử hình thành và phát triển của vùng sông nước, đây còn là kế sinh nhai của nhiều hộ gia đình dân tộc thiểu số, đặc biệt là cộng đồng người Khmer sinh sống tại xã Phước Quới, huyện Châu Thành. Đối với cư dân nơi đây, đan lát đã cứu người Khmer thoát khỏi cảnh đói nghèo đồng thời giúp con cái họ có thể học hành thành đạt.\r\n\r\nVới nguyên liệu chính là các loại tre, trúc khai thác từ khu vực địa phương, người Cà Mau kế thừa và phát huy truyền thống của cha ông đã tạo nên vô số sản phẩm độc đáo, đẹp mắt như thúng, rổ, cót... Bởi vì sản phẩm làm hoàn toàn bằng tay nên thành quả bày bán ra và được các bạn gần xa mua về làm quà du lịch cho gia đình, bạn bè chính là minh chứng rõ ràng nhất cho tay nghề khéo léo cũng như óc thẩm mỹ của những người thợ. Ngày nay, không chỉ dừng chân ở thị trường nội địa, thành phẩm đan lát còn được xuất khẩu đi các nước lân cận như Campuchia, Thái Lan.\r\n\r\nTop những nghề truyền thống ở Cà Mau có thể bạn chưa biết 3\r\nĐan lát là kế sinh nhai của nhiều hộ gia đình người Khmer tại xã Phước Quới\r\n\r\n1.4 Nghề gác kèo ong\r\n\r\nĐịa chỉ làng nghề gác kèo ong: Khu vực rừng tràm U Minh Hạ\r\n\r\nNghề gác kèo ong ở rừng U Minh Hạ được đánh giá là công việc vất vả, kỳ công nhưng cũng mang đậm tính địa phương nhất vùng đất này. Sở hữu cánh rừng tràm rộng lớn cùng điều kiện thiên nhiên cực kì thuận lợi, thế hệ đi trước tận dụng sự hiểu biết về tập tính sống của loài ong đã sáng tạo và phát triển công việc đầy thú vị nhưng cũng không kém phần gian nan và đòi hỏi tay nghề cao này.\r\n\r\nĐể gác kèo \"ăn ong\", người dân nơi đây sẽ trải qua quy trình gồm 3 giai đoạn chính. Đầu tiên là chuẩn bị hệ thống kèo bằng cây tràm và đặt theo đúng hướng nắng, hướng gió để dụ ong về làm tổ. Tiếp theo là chờ đợi từ 20 đến 30 ngày để cho vỡ tổ rồi thu về mật ngọt cùng các tảng ong non. Sở dĩ nói công việc này rất vất vả và khó khăn là vì để phá tổ ong, người thợ cần phải có kinh nghiệm nhất định trong việc phán đoán tập tính của loài vật này đồng thời thực hiện các quy trình phun khói, rạch tổ một cách nhanh chóng và chuẩn xác. Thành quả mang lại thì ai cũng biết, đó chính là món Mật ong rừng U Minh ngon thượng hạng đã được Cục Sở hữu trí tuệ công nhận là nhãn hiệu độc quyền.\r\n\r\n1.5 Nghề làm dưa bồn bồn\r\n\r\nĐịa chỉ làng nghề làm dưa bồn bồn: Xã Thanh Tùng, Tân Duyệt (huyện Đầm Dơi), xã Tân Hưng Đông (huyện Cái Nước), khu vực huyện U Minh và Trần Văn Thời\r\n\r\nChế biến dưa bồn bồn là nghề truyền thống ở Cà Mau cuối cùng góp mặt trong danh sách. Đây là nghề rất phổ biến tại xứ Mũi, do đó bạn có thể tìm thấy những ngôi làng làm dưa bồn bồn ở bất cứ đâu từ huyện U Minh, Trần Văn Thời đến Đầm Dơi, Cái Nước... Hình ảnh cánh đồng cây bồn bồn trải dài cùng món ăn hấp dẫn này từ lâu đã gắn bó sâu sắc với đời sống của người dân địa phương, thậm chí được mang vào câu ca mùi mẩn của bài \"Nhớ Đầm Dơi\" đó là: Gió đẩy gió đưa bông bồn bồn rụng trắng/Thương em một đời dãi nắng, dầm mưa.\r\n\r\nCông thức làm dưa bồn bồn tuy nhiều bước nhưng qua đôi bàn tay dày dặn kinh nghiệm của người theo nghề truyền thống thì chỉ như cái chớp mắt. Nếu có dịp ghé thăm các ngôi làng làm dưa khám phá quy trình chế biến món ăn, bạn chắc chắn sẽ choáng ngợp trước cách các cô chú sơ chế sản vật thiên nhiên rồi dùng sợi chỉ chẻ đôi chúng cho vào hủ và thêm vào nước cơm vo để lên men một cách đầy chuyên nghiệp. Bồn bồn sau khi ủ vài 3 ngày đã có thể ăn hoặc chế biến cùng nhiều món ngon khác. Món ăn hình thành trong thời kì chiến tranh khốn khó và được các làng nghề lưu truyền cho đến ngày hôm nay chính là tinh hoa ẩm thực mang trong mình nét đẹp văn hóa vùng đất này.\r\n\r\nTop những nghề truyền thống ở Cà Mau có thể bạn chưa biết 4\r\nNghề làm dưa bồn bồn đã gắn bó lâu đời với người dân Cà Mau\r\n\r\nNhững nghề truyền thống ở Cà Mau vừa được MIA.vn giới thiệu bên trên chắc chắn sẽ khiến bạn không khỏi mê đắm nét đẹp văn hóa tại tỉnh thành miền Tây sông nước này. Nhanh tay lưu ngay địa chỉ các làng nghề vào cẩm nang du lịch để có dịp ghé tham quan, trải nghiệm nhé bạn ơi!', 'assets/img/d3ccadaa80990888edc4805a0286adaf.jpeg', 1, '2026-08-20 09:39:58', 'approved'),
(11, 1, 'Khởi nghĩa Hòn Khoai (1940): Mốc son chói lọi của cách mạng Cà Mau', 'Trong tiến trình lịch sử đấu tranh giải phóng dân tộc của vùng đất Cực Nam Tổ quốc, Khởi nghĩa Hòn Khoai năm 1940 là một sự kiện lịch sử chính thống có ý nghĩa đặc biệt quan trọng. Chiến công này không chỉ đập tan ách thống trị của thực dân Pháp trên hòn đảo chiến lược mà còn trở thành biểu tượng cho tinh thần kiên cường, bất khuất của quân và dân Cà Mau. Ngày diễn ra cuộc khởi nghĩa — 23/12/1940 — sau này đã được chọn làm Ngày truyền thống cách mạng của Đảng bộ, quân và dân tỉnh Cà Mau.\r\n\r\nBối cảnh lịch sử và Người thủ lĩnh Phan Ngọc Hiển\r\n\r\nCuối năm 1940, hưởng ứng Xứ ủy Nam Kỳ về việc chuẩn bị khởi nghĩa vũ trang, Tỉnh ủy Cà Mau (khi đó thuộc tỉnh Bạc Liêu) đã chủ động xây dựng kế hoạch tác chiến. Đảo Hòn Khoai — một vị trí quân sự chiến lược có đài hải đăng kiểm soát vùng biển Tây Nam — được chọn làm địa điểm đột phá.\r\n\r\nNgười trực tiếp nhận nhiệm vụ chỉ đạo và tổ chức cuộc khởi nghĩa là thầy giáo, nhà báo, chiến sĩ cách mạng Phan Ngọc Hiển. Với sự mưu trí, ông đã gầy dựng cơ sở cách mạng ngay trong lòng địch, kết nối các binh lính người Việt bị ép làm việc cho Pháp trên đảo.\r\n\r\nDiễn biến đêm Khởi nghĩa 23/12/1940\r\n\r\nTheo kế hoạch đã thống nhất với Tỉnh ủy, đúng 23 giờ 15 phút ngày 23/12/1940, Phan Ngọc Hiển cùng các đồng chí trong Đội cảm tử đã bất ngờ nổ súng đánh chiếm Đảo Hòn Khoai:\r\n\r\nTấn công đồn hải đăng: Dưới sự chỉ huy trực tiếp của Phan Ngọc Hiển, lực lượng khởi nghĩa đã làm chủ tình hình, tiêu diệt tên sếp đồn Pháp (Olivier), thu toàn bộ vũ khí, đạn dược và quân trang của địch.\r\n\r\nMặt trận Đất Mũi: Ngay sau khi làm chủ đảo, đội quân khởi nghĩa tiến về đất liền (vùng Rạch Gốc, Mũi Cà Mau) để phối hợp cùng lực lượng tại chỗ tiếp tục mở rộng địa bàn.\r\n\r\nMặc dù do tình hình chung của Khởi nghĩa Nam Kỳ bị lộ nên cuộc khởi nghĩa tại đất liền không thể diễn ra như dự kiến, nhưng chiến thắng tại Hòn Khoai đã hoàn thành triệt để mục tiêu tiêu diệt đồn bốt địch và thu nộp vũ khí cho cách mạng.\r\n\r\nKhí tiết hiên ngang và Ý nghĩa lịch sử\r\n\r\nSau cuộc khởi nghĩa, thực dân Pháp tập trung lực lượng càn quét dữ dội. Phan Ngọc Hiển cùng 9 đồng chí trong Đội cảm tử bị địch bắt. Ngày 12/7/1941, thực dân Pháp đưa 10 chiến sĩ Hòn Khoai ra xử bắn tại thị xã Bạc Liêu.\r\n\r\nTrước họng súng kẻ thù, Phan Ngọc Hiển cùng các đồng đội vẫn giữ vững khí tiết người cộng sản. Câu nói nổi tiếng của ông trước giờ hy sinh đã đi vào lịch sử: \"Chúng tôi là những người cộng sản chiến đấu cho độc lập, tự do của dân tộc. Chúng tôi hy sinh nhưng nhất định thế hệ sau sẽ tiếp tục hoàn thành nhiệm vụ!\"\r\n\r\nDi sản để lại\r\n\r\nThắng lợi của Khởi nghĩa Hòn Khoai cùng sự hy sinh anh dũng của 10 chiến sĩ khởi nghĩa đã để lại bài học vô giá về tinh thần chủ động, tiến công cách mạng. Tên tuổi của Anh hùng Lực lượng Vũ trang Nhân dân Phan Ngọc Hiển cùng đảo Hòn Khoai ngày nay đã trở thành những địa danh, danh xưng tự hào, gắn liền với các công trình, trường học và con đường trọng điểm tại Cà Mau.', 'assets/img/a7949783345f5ed6135e1e7e03247ff6.webp', 0, '2026-08-20 10:18:09', 'pending');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`id`, `category_name`, `description`) VALUES
(1, 'Lịch sử Cà Mau', 'Các mốc sự kiện lịch sử và di tích'),
(2, 'Văn hóa & Du lịch', 'Danh lam thắng cảnh, lễ hội và ẩm thực'),
(3, 'Con người Cà Mau', 'Nét đẹp tính cách người dân địa phương'),
(4, 'Góc dân gian', 'Giai thoại Bác Ba Phi');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) DEFAULT 'approved',
  `parent_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `comments`
--

INSERT INTO `comments` (`id`, `article_id`, `user_name`, `content`, `created_at`, `status`, `parent_id`) VALUES
(1, 1, 'khanhduy', 'tdususu', '2026-08-19 02:10:08', 'approved', NULL),
(2, 1, 'khanhduy', 'reyye', '2026-08-19 02:10:42', 'approved', NULL),
(3, 1, 'khanhduy', 'shs', '2026-08-19 02:15:57', 'approved', NULL),
(4, 1, 'khanhduy', 'shs', '2026-08-19 02:19:15', 'approved', NULL),
(5, 1, 'khanhduy', 'dgsfhdgjfhk', '2026-08-19 02:26:13', 'approved', NULL),
(7, 1, 'khanhduy', ';', '2026-08-20 07:18:33', 'approved', NULL),
(8, 2, 'khanhduy', 'k', '2026-08-20 07:21:22', 'approved', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `comment_likes`
--

CREATE TABLE `comment_likes` (
  `id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `comment_likes`
--

INSERT INTO `comment_likes` (`id`, `comment_id`, `user_id`, `created_at`) VALUES
(1, 5, 2, '2026-08-20 03:50:44'),
(3, 4, 2, '2026-08-20 03:50:48'),
(4, 3, 2, '2026-08-20 03:50:49'),
(5, 2, 2, '2026-08-20 03:50:50'),
(6, 1, 2, '2026-08-20 03:50:52'),
(7, 5, 1, '2026-08-20 07:18:37');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `comment_reports`
--

CREATE TABLE `comment_reports` (
  `id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reason` varchar(255) DEFAULT 'Vi phạm quy chuẩn',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `comment_reports`
--

INSERT INTO `comment_reports` (`id`, `comment_id`, `user_id`, `reason`, `created_at`) VALUES
(1, 7, 4, 'Thông tin sai lệch', '2026-08-20 09:52:51'),
(2, 7, 4, 'Spam / Quảng cáo', '2026-08-20 09:56:09'),
(3, 2, 4, 'Spam / Quảng cáo', '2026-08-20 09:56:59'),
(4, 7, 4, 'Spam / Quảng cáo', '2026-08-20 10:11:43');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `subject` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `contacts`
--

INSERT INTO `contacts` (`id`, `name`, `email`, `message`, `created_at`, `subject`) VALUES
(1, 'Phát', 'henitusecale811@gmail.com', 'email và sdt chưa có', '2026-08-20 10:30:34', 'góp ý');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `favorites`
--

CREATE TABLE `favorites` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `favorites`
--

INSERT INTO `favorites` (`id`, `user_id`, `article_id`, `created_at`) VALUES
(3, 1, 2, '2026-08-19 03:30:22'),
(8, 1, 3, '2026-08-19 03:31:50'),
(9, 1, 1, '2026-08-19 03:34:13'),
(14, 2, 2, '2026-08-20 04:20:51'),
(15, 2, 3, '2026-08-20 04:20:59');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `likes`
--

INSERT INTO `likes` (`id`, `user_id`, `article_id`, `created_at`) VALUES
(2, 1, 2, '2026-08-19 03:30:22'),
(7, 1, 3, '2026-08-19 03:31:50'),
(9, 1, 1, '2026-08-19 03:38:17'),
(12, 2, 2, '2026-08-20 04:54:48');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `reading_history`
--

CREATE TABLE `reading_history` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `viewed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `saved_posts`
--

CREATE TABLE `saved_posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `saved_posts`
--

INSERT INTO `saved_posts` (`id`, `user_id`, `article_id`, `created_at`) VALUES
(1, 2, 1, '2026-08-20 11:19:59');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`, `email`) VALUES
(1, 'khanhduy', '$2y$10$U2dKN/h7Rrz1YTxR2oe2UObNenh0.vwO8znNI9dqzTqLGIITg1oVa', 'admin', '2026-08-18 15:40:10', NULL),
(2, 'duyduy', '$2y$10$QAHqjzerwww8aSPiJ1B4ee74hWTsvel7nUQ8.UK0YkI/Jvm7GFI3C', 'user', '2026-08-20 03:07:17', 'duyyeuai2006@gmail.com'),
(3, 'admin', '$2y$10$XugRBTsRhIh/WSH.4vtYMObltlnNibQMGffmvpJdwfId5fFcmsv6.', 'admin', '2026-08-20 08:52:04', 'maphat2911@gmail.com'),
(4, 'mphat', '$2y$10$F7H/8jbai3g692SeRs.8nu96S5Xyqzhv275newL3td9bv1L1DdRp6', 'user', '2026-08-20 09:22:26', 'henitusecale811@gmail.com');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user_reports`
--

CREATE TABLE `user_reports` (
  `id` int(11) NOT NULL,
  `reporter_id` int(11) NOT NULL,
  `reported_user_id` int(11) NOT NULL,
  `reason` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `views`
--

CREATE TABLE `views` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `article_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `article_id` (`article_id`);

--
-- Chỉ mục cho bảng `comment_likes`
--
ALTER TABLE `comment_likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_comment_like` (`comment_id`,`user_id`);

--
-- Chỉ mục cho bảng `comment_reports`
--
ALTER TABLE `comment_reports`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `article_id` (`article_id`);

--
-- Chỉ mục cho bảng `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_article` (`user_id`,`article_id`),
  ADD KEY `idx_article_id` (`article_id`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- Chỉ mục cho bảng `reading_history`
--
ALTER TABLE `reading_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `article_id` (`article_id`);

--
-- Chỉ mục cho bảng `saved_posts`
--
ALTER TABLE `saved_posts`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Chỉ mục cho bảng `user_reports`
--
ALTER TABLE `user_reports`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `views`
--
ALTER TABLE `views`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_article_view` (`user_id`,`article_id`),
  ADD KEY `idx_article_id` (`article_id`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `comment_likes`
--
ALTER TABLE `comment_likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `comment_reports`
--
ALTER TABLE `comment_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT cho bảng `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT cho bảng `reading_history`
--
ALTER TABLE `reading_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `saved_posts`
--
ALTER TABLE `saved_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `user_reports`
--
ALTER TABLE `user_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `views`
--
ALTER TABLE `views`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `reading_history`
--
ALTER TABLE `reading_history`
  ADD CONSTRAINT `reading_history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reading_history_ibfk_2` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `views`
--
ALTER TABLE `views`
  ADD CONSTRAINT `views_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `views_ibfk_2` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
