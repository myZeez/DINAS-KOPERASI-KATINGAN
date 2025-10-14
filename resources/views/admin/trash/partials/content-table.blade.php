<div class="card mt-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0">
            <i class="fas fa-trash me-2"></i>{{ $typeName }}
        </h6>
        @if ($items->count() > 0)
            <button type="button" class="btn btn-danger btn-sm empty-trash-btn" data-type="{{ $type }}"
                data-type-name="{{ $typeName }}">
                <i class="fas fa-trash-alt me-1"></i>Kosongkan Sampah
            </button>
        @endif
    </div>
    <div class="card-body">
        @if ($items->count() == 0)
            <div class="text-center py-4 text-muted">
                <i class="fas fa-check-circle fa-2x mb-3"></i>
                <p class="mb-0">Tidak ada {{ strtolower($typeName) }} di sampah</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            @foreach ($columns as $field => $label)
                                <th>{{ $label }}</th>
                            @endforeach
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                @foreach ($columns as $field => $label)
                                    <td>
                                        @if ($field == 'deleted_at')
                                            <small class="text-muted">
                                                {{ $item->deleted_at->format('d/m/Y H:i') }}
                                            </small>
                                        @elseif($field == 'rating' && isset($item->rating))
                                            <div class="d-flex align-items-center">
                                                <span class="me-1">{{ $item->rating }}</span>
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= $item->rating)
                                                        <i class="fas fa-star text-warning"></i>
                                                    @else
                                                        <i class="far fa-star text-muted"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                        @elseif($field == 'section' && isset($item->section))
                                            <span class="badge bg-info">{{ $item->section }}</span>
                                        @elseif($field == 'key' && isset($item->key))
                                            <code>{{ $item->key }}</code>
                                        @elseif($field == 'position' && isset($item->position))
                                            <span class="badge bg-secondary">{{ $item->position }}</span>
                                        @else
                                            {{ $item->$field ?? '-' }}
                                        @endif
                                    </td>
                                @endforeach
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" class="btn btn-outline-success restore-btn"
                                            data-type="{{ $type }}" data-id="{{ $item->id }}"
                                            title="Pulihkan">
                                            <i class="fas fa-undo"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-danger force-delete-btn"
                                            data-type="{{ $type }}" data-id="{{ $item->id }}"
                                            title="Hapus Permanen">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
