@props([
    'cities' => [],
    'name' => 'city_name',
    'id' => 'city_input',
    'value' => null,
    'city_id' => null,
    'required' => false,
    'placeholder' => __('common.city_placeholder'),
    'label' => __('common.city'),
    'error' => null
])

<!-- City Autocomplete Component -->
<div class="city-autocomplete-container mb-4" data-component-id="{{ $id }}">
    <label class="form-label fw-medium">
        {{ $label }}
        @if($required)
            <span class="text-danger">*</span>
        @endif
    </label>
    <div class="position-relative">
        <input type="text" 
               class="form-control {{ $error ? 'is-invalid' : '' }}" 
               id="{{ $id }}" 
               name="{{ $name }}" 
               placeholder="{{ $placeholder }}" 
               value="{{ $value }}"
               @if($required) required @endif>
        
        <input type="hidden" id="{{ $id }}_hidden" name="city_id" value="{{ old('city_id') ?? $city_id }}">
        
        @if($error)
            <div class="invalid-feedback">{{ $error }}</div>
        @endif
        
        <!-- City suggestions dropdown -->
        <div id="{{ $id }}_suggestions" class="position-absolute w-100 bg-white border rounded-3 shadow-sm" style="z-index: 1000; max-height: 200px; overflow-y: auto; display: none;"></div>
    </div>
</div>

<script>
(function() {
    var componentId = '{{ $id }}';
    var citiesData = {!! json_encode($cities) !!};
    var selectedCityId = null;
    
    function init() {
        var cityInput = document.getElementById(componentId);
        var cityIdInput = document.getElementById(componentId + '_hidden');
        var citySuggestions = document.getElementById(componentId + '_suggestions');
        
        if (!cityInput || !cityIdInput || !citySuggestions) {
            return;
        }
        
        function debounce(func, wait) {
            var timeout;
            return function() {
                var context = this;
                var args = arguments;
                clearTimeout(timeout);
                timeout = setTimeout(function() {
                    func.apply(context, args);
                }, wait);
            };
        }
        
        function performSearch(query) {
            if (!cityInput || !cityIdInput || !citySuggestions) {
                return;
            }
            
            if (query.length < 2) {
                citySuggestions.style.display = 'none';
                return;
            }
            
            var filteredCities = citiesData.filter(function(city) {
                return city.name.toLowerCase().includes(query.toLowerCase());
            }).slice(0, 20);
            
            if (filteredCities.length === 0) {
                citySuggestions.style.display = 'none';
                return;
            }
            
            citySuggestions.innerHTML = filteredCities.map(function(city) {
                return '<div class="city-suggestion p-3 border-bottom cursor-pointer hover:bg-light" data-city-id="' + city.id + '" data-city-name="' + city.name + '">' +
                    '<div class="fw-medium">' + city.name + '</div>' +
                    '</div>';
            }).join('');
            
            citySuggestions.style.display = 'block';
            
            citySuggestions.querySelectorAll('.city-suggestion').forEach(function(suggestion) {
                suggestion.addEventListener('click', function() {
                    var cityName = this.dataset.cityName;
                    var cityId = this.dataset.cityId;
                    
                    if (cityInput && cityIdInput) {
                        cityInput.value = cityName;
                        cityIdInput.value = cityId;
                        selectedCityId = cityId;
                        if (citySuggestions) {
                            citySuggestions.style.display = 'none';
                        }
                    }
                });
            });
        }
        
        var searchCities = debounce(performSearch, 300);
        
        cityInput.addEventListener('input', function(e) {
            var query = e.target.value;
            
            if (selectedCityId) {
                var selectedCity = citiesData.find(function(city) {
                    return city.id == selectedCityId;
                });
                if (!selectedCity || selectedCity.name !== query) {
                    selectedCityId = null;
                    if (cityIdInput) {
                        cityIdInput.value = '';
                    }
                }
            }
            
            searchCities(query);
        });
        
        cityInput.addEventListener('blur', function() {
            setTimeout(function() {
                if (citySuggestions) {
                    citySuggestions.style.display = 'none';
                }
            }, 200); // Small delay to allow suggestion clicks
        });
        
        var initialValue = @json($value);
        if (initialValue) {
            var initialCity = citiesData.find(function(city) {
                return city.name === initialValue;
            });
            if (initialCity && cityInput && cityIdInput) {
                cityInput.value = initialCity.name;
                cityIdInput.value = initialCity.id;
                selectedCityId = initialCity.id;
            }
        }
        
        var oldCityId = @json(old('city_id'));
        var initialCityId = @json($city_id);

        if (oldCityId) {
            var initialCity = citiesData.find(function(city) {
                return city.id == oldCityId;
            });
            if (initialCity && cityInput && cityIdInput) {
                cityInput.value = initialCity.name;
                cityIdInput.value = initialCity.id;
                selectedCityId = initialCity.id;
            }
        } else if (initialCityId) {
            var initialCity = citiesData.find(function(city) {
                return city.id == initialCityId;
            });
            if (initialCity && cityInput && cityIdInput) {
                cityInput.value = initialCity.name;
                cityIdInput.value = initialCity.id;
                selectedCityId = initialCity.id;
            }
        }
    }
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        setTimeout(init, 100);
    }
})();
</script>
