#!/bin/bash

# Define your custom theme name
export CUSTOM_BARRIO="playground_2024" # change this to your custom theme_name

# Check if the subtheme directory exists
if [ -d "../contrib/bootstrap_barrio/subtheme" ]; then
    # Copy the subtheme directory
    cp -r "../contrib/bootstrap_barrio/subtheme" "$CUSTOM_BARRIO" || exit
else
    echo "Subtheme directory not found."
    exit 1
fi

# Move into the custom subtheme directory
cd "$CUSTOM_BARRIO" || exit

# Rename files and replace strings
find . -type f -exec sh -c '
    for file; do
        new_name=$(echo "$file" | sed "s/bootstrap_barrio_subtheme/$CUSTOM_BARRIO/")
        mv "$file" "$new_name"
        sed -i "s/bootstrap_barrio_subtheme/$CUSTOM_BARRIO/g" "$new_name"
    done
' sh {} +
