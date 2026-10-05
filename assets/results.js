document.addEventListener('DOMContentLoaded', () => {
    // Set up the 3D brain model
    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(75, 1, 0.1, 1000);
    const renderer = new THREE.WebGLRenderer();
    renderer.setSize(document.getElementById('brain-model').clientWidth, 500);
    document.getElementById('brain-model').appendChild(renderer.domElement);

    // Create a simple brain sphere
    const geometry = new THREE.SphereGeometry(1, 32, 32);
    const material = new THREE.MeshPhongMaterial({
        color: 0xf5d0c5,
        wireframe: false,
        transparent: true,
        opacity: 0.7
    });
    const brain = new THREE.Mesh(geometry, material);
    scene.add(brain);

    // Add a red sphere to represent the tumor
    const tumorGeometry = new THREE.SphereGeometry(0.1, 32, 32);
    const tumorMaterial = new THREE.MeshPhongMaterial({ color: 0xff0000 });
    const tumor = new THREE.Mesh(tumorGeometry, tumorMaterial);
    tumor.position.set(0.5, 0.5, 0.5);
    scene.add(tumor);

    // Add lighting
    const ambientLight = new THREE.AmbientLight(0xffffff, 0.5);
    scene.add(ambientLight);
    const pointLight = new THREE.PointLight(0xffffff, 1);
    pointLight.position.set(5, 5, 5);
    scene.add(pointLight);

    camera.position.z = 3;

    // Add orbit controls
    const controls = new THREE.OrbitControls(camera, renderer.domElement);
    controls.enableDamping = true;
    controls.dampingFactor = 0.25;
    controls.enableZoom = true;

    function animate() {
        requestAnimationFrame(animate);
        controls.update();
        renderer.render(scene, camera);
    }
    animate();

    // Display tumor information
    const tumorInfo = {
        type: 'Glioblastoma',
        size: '4.2 cm',
        location: 'Right frontal lobe',
        grade: 'IV',
        symptoms: ['Headaches', 'Seizures', 'Vision problems', 'Personality changes'],
        treatment: 'Surgery followed by radiation and chemotherapy with Temozolomide',
        prognosis: 'Poor. 5-year survival rate is about 5%. Median survival time with standard treatment is 12-18 months.'
    };

    document.getElementById('tumor-type').textContent = tumorInfo.type;
    document.getElementById('tumor-size').textContent = tumorInfo.size;
    document.getElementById('tumor-location').textContent = tumorInfo.location;
    document.getElementById('tumor-grade').textContent = tumorInfo.grade;

    const symptomsList = document.getElementById('tumor-symptoms');
    tumorInfo.symptoms.forEach(symptom => {
        const li = document.createElement('li');
        li.textContent = symptom;
        symptomsList.appendChild(li);
    });

    document.getElementById('tumor-treatment').textContent = tumorInfo.treatment;
    document.getElementById('tumor-prognosis').textContent = tumorInfo.prognosis;

    // Responsive design
    window.addEventListener('resize', () => {
        const width = document.getElementById('brain-model').clientWidth;
        camera.aspect = width / 500;
        camera.updateProjectionMatrix();
        renderer.setSize(width, 500);
    });
});