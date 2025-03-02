<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('website_logo')->nullable()->comment('Logo website');
            $table->string('site_name')->nullable()->comment('Tên website');
            $table->string('brand_name')->nullable()->comment('Tên thương hiệu');
            $table->string('slogan')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('headquarters')->nullable()->comment('Trụ sở chính');
            $table->string('business_license')->nullable()->comment('Giấy phép kinh doanh');
            $table->string('working_hours')->nullable()->comment('Giờ làm việc');
            $table->string('facebook_link')->nullable();
            $table->string('youtube_link')->nullable();
            $table->string('instagram_link')->nullable();
            $table->string('privacy_policy_image')->nullable()->comment('Hình ảnh chính sách bảo mật');
            $table->mediumText('privacy_policy')->nullable()->comment('Chính sách bảo mật');
            $table->string('terms_of_service_image')->nullable()->comment('Hình ảnh điều khoản dịch vụ');
            $table->mediumText('terms_of_service')->nullable()->comment('Điều khoản dịch vụ');
            $table->string('introduction_image')->nullable()->comment('Hình ảnh giới thiệu');
            $table->mediumText('introduction')->nullable()->comment('Giới thiệu');
            $table->string('news_img')->nullable()->comment('Hình ảnh tin tức ');
            $table->mediumText('news')->nullable()->comment('Tin tức');
            $table->string('copyright')->nullable()->comment('Bản quyền');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
