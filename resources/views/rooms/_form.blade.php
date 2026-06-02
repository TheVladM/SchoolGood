<div class="form-field"><label class="label" for="name">Nom</label><input id="name" name="name" class="field" required value="{{ old('name', $room->name ?? '') }}"></div>
<div class="form-field"><label class="label" for="building">Bâtiment</label><input id="building" name="building" class="field" value="{{ old('building', $room->building ?? '') }}"></div>
<div class="form-field"><label class="label" for="floor">Étage / emplacement</label><input id="floor" name="floor" class="field" value="{{ old('floor', $room->floor ?? '') }}"></div>
<div class="form-field"><label class="label" for="capacity">Capacité</label><input id="capacity" name="capacity" type="number" min="1" class="field" value="{{ old('capacity', $room->capacity ?? '') }}"></div>
