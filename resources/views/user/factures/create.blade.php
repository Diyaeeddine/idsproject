<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Créer une Facture pour le Contrat #{{ $contrat->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-gray-900">

                    <form action="{{ route('factures.store', ['contrat' => $contrat->id]) }}" method="POST">
                        @csrf
                        {{-- ADD THIS ERROR DISPLAY BLOCK --}}
    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700">
            <p class="font-bold">Please fix the following errors:</p>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>&bull; {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    {{-- END OF ERROR BLOCK --}}
                        {{-- INVOICE HEADER --}}
                        <div class="flex justify-between items-start mb-8">
                            <div>
                                <h1 class="text-2xl font-bold text-gray-800">Facture {{ $invoiceNumber }}</h1>
                                <input type="hidden" name="numero_facture" value="{{ $invoiceNumber }}">
                                <img src="data:image/png;base64,..." alt="QR Code" class="w-24 h-24 mt-4"> {{-- Placeholder for QR --}}
                            </div>
                            <div class="text-right">
                                <h3 class="text-lg font-semibold">Marina Bouregreg</h3>
                                <p>Avenue de Fès, Quartier Rmel</p>
                                <p>Bab Lamrissa, Salé</p>
                                <p>Tel: 05 37 84 99 00</p>
                            </div>
                        </div>

                        {{-- DATES & ORIGIN --}}
                        <div class="grid grid-cols-3 gap-4 mb-8 pb-4 border-b">
                            <div>
                                <label for="date_facture" class="font-bold">Date de la facture :</label>
                                <input type="date" id="date_facture" name="date_facture" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ date('Y-m-d') }}">
                            </div>
                            <div>
                                <label for="date_echeance" class="font-bold">Date d'échéance :</label>
                                <input type="date" id="date_echeance" name="date_echeance" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ date('Y-m-d', strtotime('+5 days')) }}">
                            </div>
                            <div>
                                <label class="font-bold">Origine :</label>
                                <p class="mt-1">{{ $contrat->id }}</p> {{-- Example origin --}}
                            </div>
                        </div>

                        {{-- CLIENT INFO --}}
                        <div class="mb-8">
                            <h4 class="font-bold mb-2">Facturé à :</h4>
                            <p>{{ $contrat->demandeur->nom }}</p>
                            <p>Nom du Bateau: {{ $contrat->navire->nom }}</p>
                            <p>Immatriculation: {{ $contrat->navire->numero_immatriculation }}</p>
                            <p>Période: Du {{ \Carbon\Carbon::parse($contrat->date_debut)->format('d/m/Y') }} Au {{ \Carbon\Carbon::parse($contrat->date_fin)->format('d/m/Y') }}</p>
                        </div>
                        
                        {{-- INVOICE ITEMS TABLE --}}
                        <table class="w-full mb-8" id="invoice-items-table">
                            <thead>
                                <tr class="border-b bg-gray-50">
                                    <th class="text-left p-2">Description</th>
                                    <th class="p-2 w-24">Quantité</th>
                                    <th class="p-2 w-32">Prix Unitaire</th>
                                    <th class="p-2 w-32">Montant</th>
                                    <th class="p-2 w-12"></th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Rows will be added by JavaScript --}}
                            </tbody>
                        </table>
                        <button type="button" id="add-item-btn" class="mb-8 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Ajouter une ligne</button>

                        {{-- TOTALS SECTION --}}
                        <div class="flex justify-end">
                            <div class="w-full max-w-sm">
                                <div class="flex justify-between py-2 border-b">
                                    <span>Total HT</span>
                                    <span id="total-ht">0.00 DH</span>
                                    <input type="hidden" name="total_ht" id="input-total-ht" value="0">
                                </div>
                                <div class="flex justify-between py-2 border-b">
                                    <span>Taxe régionale 5%</span>
                                    <span id="taxe-regionale">0.00 DH</span>
                                     <input type="hidden" name="taxe_regionale" id="input-taxe-regionale" value="0">
                                </div>
                                <div class="flex justify-between py-2 border-b">
                                    <span>TVA 20%</span>
                                    <span id="total-tva">0.00 DH</span>
                                    <input type="hidden" name="total_tva" id="input-total-tva" value="0">
                                </div>
                                <div class="flex justify-between py-2 font-bold text-lg">
                                    <span>Total TTC</span>
                                    <span id="total-ttc">0.00 DH</span>
                                    <input type="hidden" name="total_ttc" id="input-total-ttc" value="0">
                                </div>
                            </div>
                        </div>

                        <div class="mt-12 text-center">
                            <button type="submit" class="px-6 py-3 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700">Enregistrer la Facture</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

{{-- This script is in resources/views/user/factures/create.blade.php --}}
@if (session('download_contract'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const link = document.createElement('a');
        
        link.href = "{{ route('contrats.downloadPDF', [
            'contrat' => session('download_contract')['id']
        ]) }}";
        
        link.setAttribute('download', 'contrat-{{ session('download_contract')['type'] }}-{{ session('download_contract')['id'] }}.pdf');
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });
</script>
@endif

{{-- SCRIPT FOR DYNAMIC INVOICE CALCULATIONS --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tableBody = document.querySelector('#invoice-items-table tbody');
    const addItemBtn = document.getElementById('add-item-btn');
    let itemIndex = 0;

    function createRow() {
        const row = document.createElement('tr');
        row.classList.add('border-b');
        row.innerHTML = `
            <td class="p-2"><input type="text" name="items[${itemIndex}][description]" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="Description"></td>
            <td class="p-2"><input type="number" name="items[${itemIndex}][quantite]" class="item-quantite w-full border-gray-300 rounded-md shadow-sm" placeholder="1" step="1"></td>
            <td class="p-2"><input type="number" name="items[${itemIndex}][prix_unitaire]" class="item-prix w-full border-gray-300 rounded-md shadow-sm" placeholder="0.00" step="0.01"></td>
            <td class="p-2 text-right"><span class="item-montant">0.00 DH</span></td>
            <td class="p-2 text-center"><button type="button" class="remove-item-btn text-red-500 hover:text-red-700">&times;</button></td>
        `;
        tableBody.appendChild(row);
        itemIndex++;
    }

    addItemBtn.addEventListener('click', createRow);

    tableBody.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-item-btn')) {
            e.target.closest('tr').remove();
            updateTotals();
        }
    });

    tableBody.addEventListener('input', function(e) {
        if (e.target.classList.contains('item-quantite') || e.target.classList.contains('item-prix')) {
            const row = e.target.closest('tr');
            const quantite = parseFloat(row.querySelector('.item-quantite').value) || 0;
            const prix = parseFloat(row.querySelector('.item-prix').value) || 0;
            const montant = quantite * prix;
            row.querySelector('.item-montant').textContent = montant.toFixed(2) + ' DH';
            updateTotals();
        }
    });

    function updateTotals() {
        let totalHT = 0;
        document.querySelectorAll('#invoice-items-table tbody tr').forEach(row => {
            const quantite = parseFloat(row.querySelector('.item-quantite').value) || 0;
            const prix = parseFloat(row.querySelector('.item-prix').value) || 0;
            totalHT += quantite * prix;
        });

        const taxeRegionale = totalHT * 0.05;
        const totalTVA = (totalHT + taxeRegionale) * 0.20;
        const totalTTC = totalHT + taxeRegionale + totalTVA;

        document.getElementById('total-ht').textContent = totalHT.toFixed(2) + ' DH';
        document.getElementById('taxe-regionale').textContent = taxeRegionale.toFixed(2) + ' DH';
        document.getElementById('total-tva').textContent = totalTVA.toFixed(2) + ' DH';
        document.getElementById('total-ttc').textContent = totalTTC.toFixed(2) + ' DH';

        document.getElementById('input-total-ht').value = totalHT.toFixed(2);
        document.getElementById('input-taxe-regionale').value = taxeRegionale.toFixed(2);
        document.getElementById('input-total-tva').value = totalTVA.toFixed(2);
        document.getElementById('input-total-ttc').value = totalTTC.toFixed(2);
    }
    
    createRow();
});
</script>

</x-app-layout>