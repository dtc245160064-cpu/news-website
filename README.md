# WEBSITE TIN TỨC ICTU

## 1. Giới thiệu

Dự án triển khai website tin tức bằng WordPress trên Ubuntu Server, sử dụng Docker Compose để quản lý các dịch vụ. Hệ thống hỗ trợ đăng bài, chỉnh sửa bài viết, quản lý danh mục và lưu trữ dữ liệu trong MySQL.

Dự án tích hợp Nginx Reverse Proxy, HTTPS, Monitoring, Centralized Logging và Hardening.

## 2. Công nghệ sử dụng

Ubuntu Server, Docker Engine, Docker Compose, WordPress, MySQL 8.4, phpMyAdmin, Nginx, Prometheus, Grafana, cAdvisor, Nginx Exporter, MySQL Exporter, Loki, Promtail, Git và GitHub.

## 3. Kiến trúc hệ thống

```text
Người dùng
    |
    v
Nginx :80 -- HTTP redirect --> HTTPS :443
                                  |
                                  v
                              WordPress
                                  |
                                  v
                                MySQL

phpMyAdmin :8081 --> MySQL

Monitoring:
cAdvisor ---------+
Nginx Exporter ----+--> Prometheus --> Grafana
MySQL Exporter ----+

Centralized Logging:
Docker Containers --> Promtail --> Loki --> Grafana
```

## 4. Cấu trúc thư mục

- `compose.yaml`: cấu hình các Docker container.
- `README.md`: tài liệu dự án.
- `.gitignore`: các file không đưa lên GitHub.
- `.env`: biến môi trường và mật khẩu, không đưa lên GitHub.
- `nginx/default.conf`: cấu hình Nginx Reverse Proxy và HTTPS.
- `nginx/ssl/`: chứng chỉ và khóa riêng SSL, không đưa lên GitHub.
- `prometheus/prometheus.yml`: cấu hình Prometheus.
- `loki/loki-config.yml`: cấu hình Loki.
- `promtail/promtail-config.yml`: cấu hình Promtail.

## 5. Hướng dẫn triển khai

### Bước 1: Chuẩn bị

Cài đặt Ubuntu Server, Docker Engine, Docker Compose và Git.

### Bước 2: Clone repository

```bash
git clone git@github.com:dtc245160064-cpu/news-website.git
cd news-website
```

### Bước 3: Cấu hình môi trường

Tạo file `.env` theo cấu hình yêu cầu của `compose.yaml`. Thiết lập mật khẩu riêng, an toàn trước khi khởi động.

### Bước 4: Khởi động hệ thống

```bash
docker compose config --quiet
docker compose pull
docker compose up -d
docker compose ps
```

Để bật HTTPS trên máy chủ mới, cần tạo chứng chỉ SSL và khóa riêng phù hợp, đồng thời cấu hình Nginx theo hướng dẫn trong repository.

### Bước 5: Kiểm tra

```bash
curl -I http://localhost
curl -k -I https://localhost
curl -I http://localhost:8081
curl http://localhost:9090/-/ready
curl -k -I https://localhost:3000/login
```

Lưu ý: `-k` chỉ bỏ qua bước xác minh chứng chỉ khi kiểm tra bằng curl; không nên sử dụng như biện pháp bảo mật cho môi trường production.

## 6. Địa chỉ truy cập

Thay `SERVER_IP` bằng địa chỉ IP của Ubuntu Server.

- Website: `https://SERVER_IP`
- WordPress Admin: `https://SERVER_IP/wp-admin`
- phpMyAdmin: `http://SERVER_IP:8081`
- Prometheus: `http://SERVER_IP:9090`
- Grafana: `http://SERVER_IP:3000`

HTTP tại cổng 80 tự động chuyển hướng sang HTTPS. Chứng chỉ hiện tại là chứng chỉ tự ký nên trình duyệt sẽ cảnh báo chưa xác minh được danh tính máy chủ. Chỉ truy cập nếu tin cậy máy chủ của dự án.

Các cổng quản trị và giám sát cần được giới hạn truy cập bằng firewall hoặc mạng quản trị trong môi trường triển khai thực tế.

## 7. Monitoring với Prometheus và Grafana

Prometheus thu thập metrics từ Prometheus, cAdvisor, Nginx Exporter và MySQL Exporter. Grafana sử dụng Prometheus Data Source tại `http://prometheus:9090`.

Dashboard giám sát gồm năm panel: Container CPU Usage, Container Memory Usage, Nginx Active Connections, MySQL Active Connections và MySQL Status.

Các truy vấn PromQL:

Container CPU Usage:

```promql
sum by (name) (rate(container_cpu_usage_seconds_total{name!=""}[5m])) * 100
```

Container Memory Usage:

```promql
sum by (name) (container_memory_usage_bytes{name!=""})
```

Nginx Active Connections:

```promql
nginx_connections_active
```

MySQL Active Connections:

```promql
mysql_global_status_threads_connected
```

MySQL Status:

```promql
mysql_up
```

Giá trị `mysql_up = 1` cho biết MySQL Exporter kết nối được tới MySQL.

## 8. Centralized Logging với Loki và Promtail

Promtail thu thập log từ các Docker container và gửi đến Loki. Grafana sử dụng Loki Data Source tại `http://loki:3100`.

Các truy vấn LogQL minh họa:

```logql
{container="news-nginx"}
```

```logql
{container="news-nginx"} |= "GET"
```

```logql
{container="news-nginx"} |= "error"
```

Các truy vấn được thực hiện trong Grafana Explore. Loki sử dụng cổng 3100 trong mạng Docker và không công khai trực tiếp ra host.

## 9. Hardening và bảo mật

Dự án áp dụng các biện pháp bảo mật cơ bản:

- Không đưa mật khẩu và file `.env` lên GitHub.
- Không công khai cổng MySQL 3306 trực tiếp ra host.
- Tách Docker network frontend và backend.
- Cấu hình backend network ở chế độ internal.
- Không công khai cổng Loki 3100 ra host.
- Sử dụng Nginx Reverse Proxy và HTTPS.
- Thiết lập các HTTP Security Headers.
- Sử dụng `no-new-privileges` cho các container đã cấu hình.
- MySQL Exporter sử dụng tài khoản giám sát có quyền giới hạn.
- Chứng chỉ và khóa riêng SSL được loại trừ khỏi Git.

Các Security Headers:

- `X-Frame-Options: SAMEORIGIN`
- `X-Content-Type-Options: nosniff`
- `Referrer-Policy: strict-origin-when-cross-origin`

`no-new-privileges` không đồng nghĩa với việc container chạy bằng người dùng non-root. Chứng chỉ tự ký phù hợp cho môi trường học tập, nhưng production nên sử dụng chứng chỉ được CA tin cậy cấp, quản lý secrets an toàn và giới hạn truy cập các trang quản trị.

## 10. Kiểm tra hệ thống

```bash
docker compose ps
docker compose config --quiet
curl -I http://localhost
curl -k -I https://localhost
curl -I http://localhost:8081
curl http://localhost:9090/-/ready
curl -I http://localhost:3000/login
```

## 11. Quản lý mã nguồn bằng GitHub

Repository: https://github.com/dtc245160064-cpu/news-website

Các nhóm công việc đã được commit gồm triển khai WordPress, MySQL và phpMyAdmin; cấu hình Nginx Reverse Proxy và Security Headers; triển khai Prometheus, Grafana và Monitoring; triển khai Loki, Promtail và Centralized Logging; Hardening; và bật HTTPS.

## 12. Kết luận

Dự án triển khai website tin tức ICTU trên Ubuntu Server bằng Docker Compose. Hệ thống tích hợp WordPress, MySQL, phpMyAdmin, Nginx Reverse Proxy, HTTPS, Prometheus, Grafana, Loki và Promtail.

Dự án phục vụ mục đích học tập và thực hành triển khai ứng dụng, giám sát hệ thống, quản lý log và bảo mật cơ bản.