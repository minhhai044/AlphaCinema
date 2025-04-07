<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site_setting extends Model
{
    use HasFactory;
    protected $table = 'site_settings';
    protected $fillable = [
        'website_logo',
        'site_name',
        'brand_name',
        'slogan',
        'phone',
        'email',
        'headquarters',
        'business_license',
        'working_hours',
        'facebook_link',
        'youtube_link',
        'instagram_link',
        'privacy_policy_image',
        'privacy_policy',
        'terms_of_service_image',
        'terms_of_service',
        'introduction_image',
        'introduction',
        'news',
        'news_img',
        'background_image',
        'copyright',
    ];

    public static function defaultSetting()
    {
        return [

            'website_logo' => 'theme/client/images/logo.svg',
            'site_name' => 'AlphaCinema',
            'brand_name' => 'FPT Poly ',
            'slogan' => 'Phim hay giá tốt!! ',
            'phone' => '0987654321',
            'email' => 'admin@gmail.com ',
            'headquarters' => 'Tòa nhà FPT ',
            'business_license' => 'Giấy Phép kinh doanh ',
            'working_hours' => '7:00 A.M đến 23:00 P.M',
            'facebook_link' => 'https://www.facebook.com/',
            'youtube_link' => 'https://www.youtube.com/',
            'instagram_link' => 'https://www.instagram.com/',
            'privacy_policy_image' => 'theme/client/images/logo.svg',
            // Chính sách bảo mậtmật
            'privacy_policy' => '
                <h1>Chính Sách Bảo Mật</h1>
                <p>Chào mừng bạn đến với AlphaCinema. Chúng tôi cam kết bảo vệ thông tin cá nhân của bạn khi sử dụng dịch vụ của chúng tôi.</p>
                
                <h2>1. Thu Thập Thông Tin</h2>
                <p>Chúng tôi có thể thu thập các thông tin cá nhân như họ tên, email, số điện thoại, và phương thức thanh toán khi bạn sử dụng dịch vụ.</p>
                
                <h2>2. Sử Dụng Thông Tin</h2>
                <p>Thông tin của bạn sẽ được sử dụng để:</p>
                <ul>
                    <li>Hỗ trợ đặt vé và thanh toán.</li>
                    <li>Gửi thông tin về khuyến mãi, cập nhật phim mới.</li>
                    <li>Cải thiện trải nghiệm người dùng.</li>
                </ul>
                
                <h2>3. Bảo Mật Thông Tin</h2>
                <p>Chúng tôi sử dụng các biện pháp bảo mật để bảo vệ thông tin của bạn khỏi truy cập trái phép.</p>
                
                <h2>4. Chia Sẻ Thông Tin</h2>
                <p>Chúng tôi không chia sẻ thông tin cá nhân của bạn với bên thứ ba, trừ khi có yêu cầu pháp lý.</p>
                
                <h2>5. Liên Hệ</h2>
                <p>Nếu bạn có bất kỳ câu hỏi nào về chính sách bảo mật, vui lòng liên hệ với chúng tôi qua email: support@alphacinema.vn</p>
            ',
            'terms_of_service_image' => 'theme/client/images/logo.svg',
            // Điều khoản dịch vụ 
            'terms_of_service' => ' 
            <h2>1. Giới thiệu</h2>
            <p>Chào mừng bạn đến với AlphaCinema! Khi sử dụng dịch vụ của chúng tôi, bạn đồng ý tuân thủ các điều khoản và điều kiện dưới đây. Vui lòng đọc kỹ trước khi tiếp tục sử dụng dịch vụ.</p>

            <h2>2. Định nghĩa</h2>
            <ul>
                <li><strong>AlphaCinema</strong>: Nền tảng đặt vé xem phim trực tuyến.</li>
                <li><strong>Người dùng</strong>: Cá nhân hoặc tổ chức sử dụng dịch vụ của AlphaCinema.</li>
                <li><strong>Dịch vụ</strong>: Các chức năng liên quan đến đặt vé, thanh toán và hỗ trợ khách hàng.</li>
            </ul>

            <h2>3. Điều kiện sử dụng dịch vụ</h2>
            <ul>
                <li>Người dùng phải có đủ 18 tuổi hoặc có sự giám sát của người giám hộ hợp pháp.</li>
                <li>Người dùng phải cung cấp thông tin cá nhân chính xác và đầy đủ khi đăng ký tài khoản hoặc đặt vé.</li>
                <li>Việc sử dụng dịch vụ phải tuân thủ theo quy định pháp luật và các điều khoản của AlphaCinema.</li>
            </ul>

            <h2>4. Quyền và trách nhiệm của người dùng</h2>
            <ul>
                <li>Không sử dụng dịch vụ vào mục đích gian lận, gây ảnh hưởng xấu đến hệ thống hoặc người dùng khác.</li>
                <li>Chịu trách nhiệm về tính bảo mật của tài khoản và thông tin đăng nhập.</li>
                <li>Có quyền yêu cầu hỗ trợ hoặc khiếu nại khi gặp vấn đề trong quá trình sử dụng dịch vụ.</li>
            </ul>

            <h2>5. Quyền và trách nhiệm của AlphaCinema</h2>
            <ul>
                <li>Đảm bảo cung cấp dịch vụ một cách ổn định, an toàn và bảo mật.</li>
                <li>Có quyền từ chối cung cấp dịch vụ hoặc khóa tài khoản nếu phát hiện vi phạm điều khoản sử dụng.</li>
                <li>Không chịu trách nhiệm đối với các sự cố phát sinh từ lỗi của người dùng hoặc bên thứ ba.</li>
            </ul>

            <h2>6. Chính sách hoàn vé và hủy vé</h2>
            <p>Vé đã đặt thành công không thể hoàn trả hoặc thay đổi, trừ trường hợp hệ thống hoặc rạp chiếu có sự cố. Mọi yêu cầu hỗ trợ về hoàn vé sẽ được xem xét tùy theo tình huống cụ thể.</p>

            <h2>7. Thanh toán và bảo mật thông tin</h2>
            <p>Người dùng có thể thanh toán thông qua các phương thức hỗ trợ trên hệ thống. AlphaCinema cam kết bảo mật thông tin thanh toán theo tiêu chuẩn an toàn cao nhất.</p>

            <h2>8. Giới hạn trách nhiệm</h2>
            <ul>
                <li>AlphaCinema không chịu trách nhiệm với các tổn thất do lỗi hệ thống từ bên thứ ba hoặc nguyên nhân khách quan.</li>
                <li>Người dùng chịu trách nhiệm đối với mọi hành vi sử dụng sai mục đích hoặc vi phạm điều khoản.</li>
            </ul>

            <h2>9. Thay đổi điều khoản dịch vụ</h2>
            <p>AlphaCinema có quyền thay đổi nội dung điều khoản và sẽ thông báo trên nền tảng. Việc tiếp tục sử dụng dịch vụ đồng nghĩa với việc bạn đồng ý với các điều khoản sửa đổi.</p>

            <h2>10. Liên hệ</h2>
            <p>Nếu bạn có bất kỳ câu hỏi nào về điều khoản dịch vụ, vui lòng liên hệ:</p>
            <p>
                Email: <a href="mailto:support@alphacinema.vn">support@alphacinema.vn</a><br>
                Hotline: 1900 1234<br>
                Website: <a href="https://www.alphacinema.vn">www.alphacinema.vn</a>
            </p>

            <p><strong>Cảm ơn bạn đã sử dụng dịch vụ của AlphaCinema!</strong></p>',
            // Ảnh Giới thiệuthiệu
            'introduction_image' => 'theme/client/images/logo.svg',
            // Giới thiệu 
            'introduction' => '
            <h1>Chào mừng đến với AlphaCinema</h1>
            <p><strong>AlphaCinema</strong> là nền tảng đặt vé xem phim trực tuyến nhanh chóng và tiện lợi, giúp bạn trải nghiệm những bộ phim bom tấn một cách dễ dàng.</p>
            <h2>Vì sao chọn AlphaCinema?</h2>
            <ul>
                <li>Đặt vé nhanh chóng chỉ trong vài cú click.</li>
                <li>Cập nhật lịch chiếu và phim mới nhất mỗi ngày.</li>
                <li>Ưu đãi hấp dẫn dành cho thành viên.</li>
                <li>Hệ thống thanh toán an toàn và đa dạng.</li>
            </ul>
            <h2>Liên hệ với chúng tôi</h2>
            <p>Email: support@alphacinema.vn</p>
            <p>Hotline: 1900 1234</p>
            <p>Đặt vé ngay</p>',
            // Ảnh Tin túc 
            'news_img' => 'theme/client/images/logo.svg',
            // Giới thiệu 
            'news' => '
            <section>
    <h2>1. Bom tấn tháng 3: "Siêu Anh Hùng Trỗi Dậy" sẵn sàng khuấy đảo phòng vé</h2>
    <p>Bộ phim hành động được mong chờ nhất tháng 3, "Siêu Anh Hùng Trỗi Dậy", sẽ chính thức ra rạp vào ngày 10/3. Với dàn diễn viên đình đám và kỹ xảo mãn nhãn, bộ phim hứa hẹn sẽ mang đến những trải nghiệm điện ảnh tuyệt vời.</p>
</section>

<section>
    <h2>2. Khuyến mãi cực sốc: Mua 2 vé tặng 1 bắp rang</h2>
    <p>Từ ngày 5/3 đến 15/3, khi mua 2 vé xem phim bất kỳ tại ALphaCinema, bạn sẽ nhận ngay một phần bắp rang miễn phí. Đừng bỏ lỡ cơ hội thưởng thức phim hay kèm theo món ăn vặt hấp dẫn!</p>
</section>

<section>
    <h2>3. ALphaCinema khai trương rạp mới tại TP. HCM</h2>
    <p>ALphaCinema chính thức mở thêm chi nhánh mới tại Quận 7, TP. HCM vào ngày 20/3. Với hệ thống âm thanh Dolby Atmos và màn hình IMAX hiện đại, đây chắc chắn là điểm đến lý tưởng cho các tín đồ điện ảnh.</p>
</section>

<section>
    <h2>4. Review hot: "Hành trình về nhà" Bộ phim cảm động lấy nước mắt khán giả</h2>
    <p>Bộ phim "Hành trình về nhà" đã chính thức ra mắt và nhanh chóng gây sốt nhờ nội dung ý nghĩa cùng diễn xuất ấn tượng. Bộ phim hiện đang nhận được số điểm rất cao trên các trang đánh giá phim uy tín.</p>
</section>

<section>
    <h2>5. Lịch chiếu và đặt vé nhanh chóng</h2>
    <p>Để không bỏ lỡ những bộ phim hấp dẫn nhất, hãy truy cập website chính thức của ALphaCinema để xem lịch chiếu và đặt vé ngay hôm nay!</p>
</section>
',
'background_image'=>'theme/client/images/bg.png',
            'copyright' => ' Bản quyền thuộc về  ',
        ];
    }
}
