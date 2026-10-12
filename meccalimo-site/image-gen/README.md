# Mecca Limo image generation

Every image starts from a real fleet reference photo in `refs/` so the vehicle stays consistent and realistic.

Run: `./make-image.sh <sprinter|navigator|suburban|sedan> <name> <1024x1024|1536x1024|1024x1536> "<scene>"`

Rules baked into the prompt:
- Vehicle must match the reference exactly (no redesigns, no stretch limos or party buses).
- Photoreal automotive look, light and shadows matching the scene.
- No text or logos in the generated image. Brand text and the phone number are added afterwards, so spelling is always exact.
- Never publish prices.

Good scenes are specific and real to Charleston, for example:
- "parked at the curb of a cobblestone street in downtown Charleston's French Quarter, early morning soft light"
- "at the arrivals curb of Charleston International Airport, daytime, clean modern terminal behind"
- "in front of an oak-lined plantation wedding venue entrance, golden hour, string lights in the trees"
