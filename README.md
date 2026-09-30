# WEBSITE TIN TUC ICTU

## 1. Gioi thieu

Du an trien khai Website tin tuc bang WordPress tren Ubuntu Server va Docker.

Website ho tro:
- Quan tri vien dang bai
- Quan ly bai viet
- Quan ly danh muc
- Luu tru du lieu tren MySQL
- Quan ly co so du lieu bang phpMyAdmin

## 2. Cong nghe su dung

- Ubuntu Server
- Docker
- Docker Compose
- WordPress
- MySQL
- phpMyAdmin
- Nginx Reverse Proxy
- Prometheus
- Grafana
- Loki
- Promtail
- Git
- GitHub

## 3. Kien truc du an

Browser
|
v
Nginx Reverse Proxy
|
v
WordPress
|
v
MySQL

He thong giam sat:
Prometheus -> Grafana

He thong log:
Promtail -> Loki -> Grafana

## 4. Cau truc thu muc

news-website/
- compose.yaml
- README.md
- nginx/
- prometheus/
- loki/
- promtail/

File .env chua cac bien moi truong va mat khau, khong duoc dua len GitHub.

## 5. Cac dich vu

- WordPress: Website tin tuc
- MySQL: Co so du lieu
- phpMyAdmin: Quan ly MySQL
- Nginx: Reverse Proxy
- Prometheus: Thu thap metrics
- Grafana: Dashboard giam sat
- Loki: He thong log tap trung
- Promtail: Thu thap log

## 6. Khoi dong he thong

Tao file .env voi cac bien:

MYSQL_ROOT_PASSWORD
MYSQL_DATABASE
MYSQL_USER
MYSQL_PASSWORD

Sau do chay:

docker compose pull
docker compose up -d

Kiem tra:

docker compose ps

## 7. Bao mat

Du an ap dung:
- Khong dua mat khau len GitHub
- Bien moi truong luu trong .env
- Database khong expose port truc tiep ra host
- Tach frontend network va backend network
- Nginx security headers
- Container hardening
- Principle of least privilege

## 8. Trang thai

Giai doan 1:
- WordPress hoat dong
- MySQL hoat dong
- phpMyAdmin hoat dong
- Website co bai viet va danh muc
- WordPress ket noi MySQL thanh cong

Cac giai doan tiep theo:
- Nginx Reverse Proxy
- Prometheus + Grafana
- Loki + Promtail + LogQL
- Hardening# News Website Deployment

Du an trien khai Website tin tuc bang Docker Compose.

## Chuc nang

- Website tin tuc WordPress
- Quan ly bai viet
- Quan ly danh muc
- Admin dang bai
- MySQL Database
- phpMyAdmin
- Nginx Reverse Proxy
- Prometheus
- Grafana
- Loki
- Promtail
- Hardening

## Kien truc

Client -> Nginx -> WordPress -> MySQL

Monitoring:
Prometheus -> Grafana

Logging:
Promtail -> Loki -> Grafana
