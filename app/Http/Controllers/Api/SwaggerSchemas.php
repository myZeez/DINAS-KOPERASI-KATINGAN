<?php

namespace App\Http\Controllers\Api;

/**
 * @OA\Schema(
 *     schema="News",
 *     type="object",
 *     title="News",
 *     description="Model berita",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Judul Berita"),
 *     @OA\Property(property="slug", type="string", example="judul-berita"),
 *     @OA\Property(property="content", type="string", example="Konten berita lengkap..."),
 *     @OA\Property(property="image", type="string", nullable=true, example="news/image.jpg"),
 *     @OA\Property(property="status", type="string", enum={"draft", "published"}, example="published"),
 *     @OA\Property(property="published_at", type="string", format="datetime", example="2025-01-01T12:00:00.000000Z"),
 *     @OA\Property(property="user_id", type="integer", example=1),
 *     @OA\Property(property="created_at", type="string", format="datetime", example="2025-01-01T12:00:00.000000Z"),
 *     @OA\Property(property="updated_at", type="string", format="datetime", example="2025-01-01T12:00:00.000000Z"),
 *     @OA\Property(
 *         property="user",
 *         type="object",
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="name", type="string", example="Admin User")
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="Gallery",
 *     type="object",
 *     title="Gallery",
 *     description="Model galeri foto",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Judul Foto"),
 *     @OA\Property(property="description", type="string", nullable=true, example="Deskripsi foto"),
 *     @OA\Property(property="image", type="string", example="galleries/photo.jpg"),
 *     @OA\Property(property="category", type="string", enum={"kegiatan", "rapat", "acara", "fasilitas"}, example="kegiatan"),
 *     @OA\Property(property="tags", type="string", nullable=true, example="koperasi,meeting"),
 *     @OA\Property(property="status", type="integer", enum={0, 1}, example=1),
 *     @OA\Property(property="is_featured", type="boolean", example=false),
 *     @OA\Property(property="created_at", type="string", format="datetime", example="2025-01-01T12:00:00.000000Z"),
 *     @OA\Property(property="updated_at", type="string", format="datetime", example="2025-01-01T12:00:00.000000Z")
 * )
 *
 * @OA\Schema(
 *     schema="Review",
 *     type="object",
 *     title="Review",
 *     description="Model ulasan/review",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *     @OA\Property(property="rating", type="integer", minimum=1, maximum=5, example=5),
 *     @OA\Property(property="message", type="string", example="Pelayanan sangat memuaskan"),
 *     @OA\Property(property="created_at", type="string", format="datetime", example="2025-01-01T12:00:00.000000Z"),
 *     @OA\Property(property="updated_at", type="string", format="datetime", example="2025-01-01T12:00:00.000000Z")
 * )
 *
 * @OA\Schema(
 *     schema="Profile",
 *     type="object",
 *     title="Profile",
 *     description="Model profil organisasi",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Dinas Koperasi Katingan"),
 *     @OA\Property(property="description", type="string", example="Deskripsi organisasi"),
 *     @OA\Property(property="address", type="string", example="Alamat lengkap"),
 *     @OA\Property(property="phone", type="string", example="+62123456789"),
 *     @OA\Property(property="email", type="string", format="email", example="info@dinaskoperasi.gov.id"),
 *     @OA\Property(property="website", type="string", nullable=true, example="https://dinaskoperasi.gov.id"),
 *     @OA\Property(property="logo", type="string", nullable=true, example="logos/logo.png"),
 *     @OA\Property(property="created_at", type="string", format="datetime", example="2025-01-01T12:00:00.000000Z"),
 *     @OA\Property(property="updated_at", type="string", format="datetime", example="2025-01-01T12:00:00.000000Z")
 * )
 *
 * @OA\Schema(
 *     schema="Structure",
 *     type="object",
 *     title="Structure",
 *     description="Model struktur organisasi",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="position", type="string", example="Kepala Dinas"),
 *     @OA\Property(property="nip", type="string", nullable=true, example="123456789"),
 *     @OA\Property(property="level", type="string", example="Kepala"),
 *     @OA\Property(property="rank", type="string", nullable=true, example="IV/a"),
 *     @OA\Property(property="photo", type="string", nullable=true, example="structure/photo.jpg"),
 *     @OA\Property(property="parent_id", type="integer", nullable=true, example=null),
 *     @OA\Property(property="is_active", type="boolean", example=true),
 *     @OA\Property(property="created_at", type="string", format="datetime", example="2025-01-01T12:00:00.000000Z"),
 *     @OA\Property(property="updated_at", type="string", format="datetime", example="2025-01-01T12:00:00.000000Z")
 * )
 */
class SwaggerSchemas
{
    // This class is only for Swagger documentation purposes
}
