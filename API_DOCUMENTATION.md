# Dinas Koperasi API Documentation

## Overview
API documentation untuk sistem Dinas Koperasi Katingan menggunakan Laravel dengan Swagger/OpenAPI 3.0.

## Base URL
```
http://localhost:8000/api/v1
```

## Documentation URL
```
http://localhost:8000/api/documentation
```

## Authentication
Untuk endpoint admin, gunakan Bearer Token authentication:
```
Authorization: Bearer {your-token}
```

## Available Endpoints

### News API
- `GET /api/v1/news` - Daftar berita
- `GET /api/v1/news/{id}` - Detail berita
- `POST /api/v1/admin/news` - Buat berita (Admin)

### Gallery API
- `GET /api/v1/galleries` - Daftar galeri
- `GET /api/v1/galleries/{id}` - Detail galeri
- `POST /api/v1/admin/galleries` - Upload foto (Admin)

### Reviews API
- `GET /api/v1/reviews` - Daftar ulasan
- `POST /api/v1/reviews` - Kirim ulasan
- `GET /api/v1/reviews/statistics` - Statistik ulasan

## Example Requests

### Get News List
```bash
curl -X GET "http://localhost:8000/api/v1/news?page=1&per_page=10" \
  -H "Accept: application/json"
```

### Create Review
```bash
curl -X POST "http://localhost:8000/api/v1/reviews" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com", 
    "rating": 5,
    "message": "Pelayanan sangat memuaskan"
  }'
```

### Upload Gallery Photo (Admin)
```bash
curl -X POST "http://localhost:8000/api/v1/admin/galleries" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json" \
  -F "title=Foto Kegiatan" \
  -F "description=Deskripsi foto" \
  -F "category=kegiatan" \
  -F "status=1" \
  -F "image=@/path/to/image.jpg"
```

## Response Format

### Success Response
```json
{
  "success": true,
  "message": "Success",
  "data": {
    // response data
  }
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error message",
  "errors": {
    // validation errors (if any)
  }
}
```

## Status Codes
- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `500` - Internal Server Error
