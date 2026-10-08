# WEBSITE TIN TỨC ICTU

## 1. Giới thiệu

Dự án triển khai website tin tức bằng WordPress trên Ubuntu Server, sử dụng Docker Compose để quản lý các dịch vụ.

Website hỗ trợ:
- Quản trị viên đăng bài viết.
- Quản lý và chỉnh sửa bài viết.
- Quản lý danh mục tin tức.
- Lưu trữ dữ liệu trong MySQL.
- Quản lý cơ sở dữ liệu qua phpMyAdmin.

Dự án tích hợp Reverse Proxy, Monitoring, Centralized Logging và Hardening.

## 2. Công nghệ sử dụng

- Ubuntu Server
- Docker Engine và Docker Compose
- WordPress
- MySQL 8.4
- phpMyAdmin
- Nginx Reverse Proxy
- Prometheus
- Grafana
- cAdvisor
- Nginx Prometheus Exporter
- MySQL Exporter
- Loki
- Promtail
- Git và GitHub

## 3. Kiến trúc hệ thống

```text
                  Người dùng
                      |
                      v
                  Nginx :80
                      |
                      v
                   WordPress
                      |
                      v
                    MySQL
                      ^
                      |
                  phpMyAdmin


Monitoring:
cAdvisor ---------+
Nginx Exporter ----+--> Prometheus --> Grafana
MySQL Exporter ----+
Prometheus --------+


Centralized Logging:
Docker Containers --> Promtail --> Loki --> Grafana


## 4. Cấu trúc thư mục

- compose.yaml: Cấu hình các Docker container.
- README.md: Tài liệu hướng dẫn dự án.
- .gitignore: Danh sách file không đưa lên GitHub.
- .env: Biến môi trường và mật khẩu.
- nginx/default.conf: Cấu hình Nginx Reverse Proxy.
- prometheus/prometheus.yml: Cấu hình Prometheus.
- loki/loki-config.yml: Cấu hình Loki.
- promtail/promtail-config.yml: Cấu hình Promtail.

File .env không được đưa lên GitHub.

## 5. Hướng dẫn triển khai

### Bước 1: Chuẩn bị

Cài đặt Ubuntu Server, Docker Engine, Docker Compose và Git.

### Bước 2: Clone repository

git clone git@github.com:dtc245160064-cpu/news-website.git

cd news-website

### Bước 3: Tạo file .env

Các biến môi trường cần thiết:

- MYSQL_ROOT_PASSWORD
- MYSQL_DATABASE
- MYSQL_USER
- MYSQL_PASSWORD

Cần thiết lập mật khẩu riêng trước khi khởi động.

### Bước 4: Khởi động hệ thống

docker compose config --quiet

docker compose pull

docker compose up -d

### Bước 5: Kiểm tra

docker compose ps

curl -I http://localhost

Lưu ý: Khi triển khai mới cần hoàn tất cài đặt WordPress và cấu hình Grafana.

## 6. Địa chỉ truy cập

Thay SERVER_IP bằng địa chỉ IP của Ubuntu Server.

- Website: http://SERVER_IP
- WordPress Admin: http://SERVER_IP/wp-admin
- phpMyAdmin: http://SERVER_IP:8081
- Prometheus: http://SERVER_IP:9090
- Grafana: http://SERVER_IP:3000

MySQL và Loki không công khai cổng trực tiếp ra host.

## 7. Monitoring với Prometheus và Grafana

Prometheus thu thập metrics từ các thành phần:

- Prometheus
- cAdvisor
- Nginx Exporter
- MySQL Exporter

Grafana sử dụng Prometheus Data Source:

http://prometheus:9090

Dashboard giám sát gồm 5 panel:

1. Container CPU Usage
2. Container Memory Usage
3. Nginx Active Connections
4. MySQL Active Connections
5. MySQL Status

Các truy vấn PromQL:

Container CPU Usage:

    sum by (name) (rate(container_cpu_usage_seconds_total{name!=""}[5m])) * 100

Container Memory Usage:

    sum by (name) (container_memory_usage_bytes{name!=""})

Nginx Active Connections:

    nginx_connections_active

MySQL Active Connections:

    mysql_global_status_threads_connected

MySQL Status:

    mysql_up

Giá trị mysql_up = 1 cho biết MySQL Exporter kết nối được tới MySQL.

## 8. Centralized Logging với Loki và Promtail

Promtail thu thập log từ các Docker container và gửi đến Loki.

Grafana sử dụng Loki Data Source:

http://loki:3100

Ba truy vấn LogQL minh họa:

Truy vấn 1: Xem toàn bộ log Nginx

    {container="news-nginx"}

Truy vấn 2: Lọc các HTTP GET request

    {container="news-nginx"} |= "GET"

Truy vấn 3: Tìm log chứa từ khóa error

    {container="news-nginx"} |= "error"

Các truy vấn được thực hiện trong Grafana Explore.

Loki sử dụng cổng 3100 trong mạng Docker và không công khai trực tiếp ra host.

## 9. Hardening và bảo mật

Dự án đã áp dụng các biện pháp bảo mật sau:

- Không đưa mật khẩu và file .env lên GitHub.
- MySQL không mở cổng 3306 trực tiếp ra host.
- Tách Docker network thành frontend và backend.
- Backend network được cấu hình internal.
- Loki không công khai cổng 3100 ra host.
- Nginx Reverse Proxy sử dụng HTTP Security Headers.
- WordPress, Nginx và phpMyAdmin bật no-new-privileges.
- MySQL Exporter sử dụng tài khoản giám sát có quyền giới hạn.

Các Security Headers đã kiểm tra:

- X-Frame-Options: SAMEORIGIN
- X-Content-Type-Options: nosniff
- Referrer-Policy: strict-origin-when-cross-origin

Lưu ý: no-new-privileges không đồng nghĩa với việc container
đã chạy bằng người dùng non-root.

Môi trường production cần bổ sung HTTPS, quản lý secrets,
giới hạn truy cập quản trị và chính sách sao lưu dữ liệu.

## 10. Kiểm tra hệ thống

Kiểm tra trạng thái container:

    docker compose ps

Kiểm tra cấu hình Docker Compose:

    docker compose config --quiet

Kiểm tra website:

    curl -I http://localhost

Kiểm tra phpMyAdmin:

    curl -I http://localhost:8081

Kiểm tra Prometheus:

    curl http://localhost:9090/-/ready

Kiểm tra Grafana:

    curl -I http://localhost:3000/login

## 11. Quản lý mã nguồn bằng GitHub

Repository:

https://github.com/dtc245160064-cpu/news-website

Các nhóm công việc đã được commit:

1. Triển khai WordPress, MySQL và phpMyAdmin.
2. Cấu hình Nginx Reverse Proxy và Security Headers.
3. Triển khai Prometheus, Grafana và Monitoring.
4. Triển khai Loki, Promtail và Centralized Logging.
5. Hardening, cách ly Loki và giới hạn đặc quyền container.

## 12. Kết luận

Dự án đã triển khai website tin tức ICTU bằng WordPress
trên Ubuntu Server với Docker Compose.

Hệ thống tích hợp MySQL, phpMyAdmin, Nginx Reverse Proxy,
Prometheus, Grafana, Loki và Promtail.

Các dịch vụ chính đã được kiểm tra hoạt động và hệ thống
đã áp dụng các biện pháp Hardening cơ bản.

Dự án phục vụ mục đích học tập và thực hành triển khai
ứng dụng, giám sát hệ thống, quản lý log và bảo mật.
